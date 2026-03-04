<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\AgencyWallet;
use App\Models\AgencyWalletTransaction;
use App\Models\Artist;
use App\Models\PV;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:gestionnaire');
    }

    public function index()
    {
        $gestionnaire = Auth::user();
        $wallet = AgencyWallet::with('transactions.pv')
            ->where('agency_id', $gestionnaire->agency_id)
            ->first();

        $transactions = AgencyWalletTransaction::with('pv')
            ->whereHas('wallet', function ($query) use ($gestionnaire) {
                $query->where('agency_id', $gestionnaire->agency_id);
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('blades.gestionnaire.wallet.index', compact('wallet', 'transactions'));
    }

    public function confirmPayment(Request $request, $pvId)
    {
        $gestionnaire = Auth::user();
        $pv = PV::where('agency_id', $gestionnaire->agency_id)->with('mission')->findOrFail($pvId);

        // Check if agent confirmed payment first
        if (!$pv->agent_payment_confirmed) {
            return redirect()->back()->withErrors(['payment' => 'Agent must confirm payment receipt before gestionnaire can validate it.']);
        }

        // Check if payment already confirmed
        if ($pv->payment_status === 'VALIDATED') {
            return redirect()->back()->withErrors(['payment' => 'Payment has already been confirmed for this PV.']);
        }

        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        // Verify that payment amount equals total amount exactly (must pay exactly)
        $totalAmount = $pv->total_amount;
        $receivedAmount = $data['amount'];
        $difference = abs($receivedAmount - $totalAmount);

        if ($difference > 0.01) { // Allow small floating point differences
            if ($receivedAmount < $totalAmount) {
                $remainingAmount = $totalAmount - $receivedAmount;
                return redirect()->back()->withErrors(['amount' => 'Cannot confirm payment: Amount received (' . number_format($receivedAmount, 2) . ' DZD) is less than required amount (' . number_format($totalAmount, 2) . ' DZD). Remaining amount: ' . number_format($remainingAmount, 2) . ' DZD. Payment must be complete before validation.']);
            } else {
                return redirect()->back()->withErrors(['amount' => 'Cannot confirm payment: Amount received (' . number_format($receivedAmount, 2) . ' DZD) exceeds required amount (' . number_format($totalAmount, 2) . ' DZD). Payment amount must match exactly.']);
            }
        }

        // Also verify that agent confirmed amount matches
        $agentConfirmedAmount = $pv->cash_received_amount ?? 0;
        if (abs($agentConfirmedAmount - $totalAmount) > 0.01) {
            return redirect()->back()->withErrors(['amount' => 'Cannot confirm payment: Agent confirmed amount (' . number_format($agentConfirmedAmount, 2) . ' DZD) does not match required amount (' . number_format($totalAmount, 2) . ' DZD). Payment must be complete.']);
        }

        $wallet = AgencyWallet::firstOrCreate(
            ['agency_id' => $gestionnaire->agency_id],
            ['balance' => 0]
        );

        // Check if transaction already exists for this PV
        $existingTransaction = AgencyWalletTransaction::where('pv_id', $pv->id)
            ->where('direction', 'IN')
            ->first();

        if ($existingTransaction) {
            return redirect()->back()->withErrors(['payment' => 'Payment has already been confirmed for this PV.']);
        }

        DB::transaction(function () use ($pv, $wallet, $data, $gestionnaire) {
            $agency = $gestionnaire->agency;
            $agencyBankAccount = $agency && $agency->bank_account_number ? $agency->bank_account_number : 'N/A';
            
            $pv->cash_received_amount = $data['amount'];
            $pv->payment_status = 'VALIDATED';
            $pv->save();

            $wallet->balance = $wallet->balance + $data['amount'];
            $wallet->last_transaction = now();
            $wallet->save();

            AgencyWalletTransaction::create([
                'agency_wallet_id' => $wallet->id,
                'pv_id' => $pv->id,
                'direction' => 'IN',
                'amount' => $data['amount'],
                'description' => 'Payment confirmed for PV #' . $pv->id . ' - To agency account: ' . $agencyBankAccount,
            ]);

            // Notify agent
            if ($pv->agent && $pv->agent->user) {
                NotificationService::send(
                    $pv->agent->user,
                    'PV Payment validated',
                    'Gestionnaire validated payment for PV #' . $pv->id . ' and added to agency wallet.',
                    [
                        'type' => 'pv_payment_validated',
                        'pv_id' => $pv->id,
                        'link' => route('agent.pvs.show', $pv->id),
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'Payment has been confirmed and added to agency wallet.');
    }

    public function releasePayment($pvId)
    {
        $gestionnaire = Auth::user();
        $pv = PV::with(['artworkUsages.artwork.artist', 'mission'])
            ->where('agency_id', $gestionnaire->agency_id)
            ->findOrFail($pvId);

        if (!$pv->canReleaseFunds()) {
            return redirect()->back()->withErrors(['pv' => 'Payment must be validated before releasing funds.']);
        }

        // Check if funds already released
        if ($pv->funds_released_at !== null) {
            return redirect()->back()->withErrors(['pv' => 'Funds have already been released for this PV.']);
        }

        // Verify that payment amount equals total amount exactly (must pay exactly)
        $pvTotalAmount = $pv->total_amount;
        $receivedAmount = $pv->cash_received_amount ?? 0;
        $difference = abs($receivedAmount - $pvTotalAmount);

        if ($difference > 0.01) { // Allow small floating point differences
            if ($receivedAmount < $pvTotalAmount) {
                $remainingAmount = $pvTotalAmount - $receivedAmount;
                return redirect()->back()->withErrors(['pv' => 'Cannot release funds: Payment is incomplete. Amount received (' . number_format($receivedAmount, 2) . ' DZD) is less than required amount (' . number_format($pvTotalAmount, 2) . ' DZD). Remaining amount: ' . number_format($remainingAmount, 2) . ' DZD. Payment must be complete before releasing funds to artists.']);
            } else {
                return redirect()->back()->withErrors(['pv' => 'Cannot release funds: Payment amount (' . number_format($receivedAmount, 2) . ' DZD) exceeds required amount (' . number_format($pvTotalAmount, 2) . ' DZD). Payment must match exactly.']);
            }
        }

        $wallet = AgencyWallet::firstOrCreate(
            ['agency_id' => $gestionnaire->agency_id],
            ['balance' => 0]
        );

        $artistTotals = $pv->artistTotals();
        $totalAmount = array_sum($artistTotals);

        if ($wallet->balance < $totalAmount) {
            return redirect()->back()->withErrors(['wallet' => 'Insufficient agency wallet balance.']);
        }

        DB::transaction(function () use ($pv, $wallet, $artistTotals, $totalAmount) {
            foreach ($artistTotals as $artistId => $amount) {
                $artist = Artist::find($artistId);
                $bankAccountNumber = $artist && $artist->bank_account_number ? $artist->bank_account_number : 'N/A';
                
                Transaction::create([
                    'pv_id' => $pv->id,
                    'artist_id' => $artistId,
                    'type' => 'PV_PAYMENT',
                    'amount' => $amount,
                    'payment_method' => $pv->payment_method ?? 'CASH',
                    'payment_status' => 'VALIDATED',
                    'description' => 'Payment from PV #' . $pv->id . ' - ' . $pv->shop_name . ' - To account: ' . $bankAccountNumber,
                ]);

                $artistWallet = Wallet::firstOrCreate(
                    ['artist_id' => $artistId],
                    ['balance' => 0]
                );
                $artistWallet->balance = $artistWallet->balance + $amount;
                $artistWallet->last_transaction = now();
                $artistWallet->save();

                AgencyWalletTransaction::create([
                    'agency_wallet_id' => $wallet->id,
                    'pv_id' => $pv->id,
                    'direction' => 'OUT',
                    'amount' => $amount,
                    'description' => 'Released to artist #' . $artistId . ' - Account: ' . $bankAccountNumber,
                ]);
            }

            $wallet->balance = $wallet->balance - $totalAmount;
            $wallet->last_transaction = now();
            $wallet->save();

            $pv->markFundsReleased();

            if ($pv->mission) {
                $pv->mission->status = 'DONE';
                $pv->mission->save();
            }
        });

        $artists = Artist::with('user')
            ->whereIn('id', array_keys($artistTotals))
            ->get()
            ->keyBy('id');

        foreach ($artistTotals as $artistId => $amount) {
            $artist = $artists->get($artistId);
            if ($artist && $artist->user) {
                $bankAccountNumber = $artist->bank_account_number ? $artist->bank_account_number : 'N/A';
                NotificationService::send(
                    $artist->user,
                    'Funds released from PV #' . $pv->id,
                    'Your wallet received ' . number_format($amount, 2, '.', ' ') . ' DA from PV #' . $pv->id . '. Payment will be sent to account: ' . $bankAccountNumber,
                    [
                        'type' => 'pv_funds_released',
                        'pv_id' => $pv->id,
                        'amount' => $amount,
                        'link' => route('artist.wallet'),
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Funds released to artists.');
    }
}


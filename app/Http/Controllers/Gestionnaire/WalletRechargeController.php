<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\WalletRechargeRequest;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;

class WalletRechargeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:gestionnaire');
    }

    public function index()
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        // Get all pending recharge requests from artists in this agency
        $pendingRequests = WalletRechargeRequest::with(['artist.user', 'artist.agency'])
            ->whereHas('artist', function($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedRequests = WalletRechargeRequest::with(['artist.user', 'artist.agency', 'approver'])
            ->whereHas('artist', function($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->where('status', 'APPROVED')
            ->orderBy('approved_at', 'desc')
            ->limit(20)
            ->get();

        $rejectedRequests = WalletRechargeRequest::with(['artist.user', 'artist.agency', 'approver'])
            ->whereHas('artist', function($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->where('status', 'REJECTED')
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return view('blades.gestionnaire.wallet-recharge.index', compact('pendingRequests', 'approvedRequests', 'rejectedRequests'));
    }

    public function show($id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $request = WalletRechargeRequest::with(['artist.user', 'artist.agency'])
            ->whereHas('artist', function($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->findOrFail($id);

        return view('blades.gestionnaire.wallet-recharge.show', compact('request'));
    }

    public function approve(Request $request, $id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $rechargeRequest = WalletRechargeRequest::with('artist')
            ->whereHas('artist', function($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->findOrFail($id);
        $rechargeRequest->loadMissing('artist.user');

        if (!$rechargeRequest->canBeApproved()) {
            return redirect()->back()->withErrors(['request' => 'This request cannot be approved.']);
        }

        DB::transaction(function () use ($rechargeRequest, $gestionnaire) {
            // Update request status
            $rechargeRequest->status = 'APPROVED';
            $rechargeRequest->approved_by = $gestionnaire->id;
            $rechargeRequest->approved_at = now();
            $rechargeRequest->save();

            // Add amount to artist wallet
            $wallet = Wallet::firstOrCreate(
                ['artist_id' => $rechargeRequest->artist_id],
                ['balance' => 0]
            );
            $wallet->balance += $rechargeRequest->amount;
            $wallet->last_transaction = now();
            $wallet->save();

            // Create transaction record
            $agency = $rechargeRequest->artist->agency;
            $agencyBankAccount = $agency && $agency->bank_account_number ? $agency->bank_account_number : 'N/A';
            
            Transaction::create([
                'artist_id' => $rechargeRequest->artist_id,
                'type' => 'WALLET_RECHARGE',
                'amount' => $rechargeRequest->amount,
                'payment_method' => 'WALLET_RECHARGE',
                'payment_status' => 'VALIDATED',
                'description' => 'Wallet recharge request #' . $rechargeRequest->id . ' approved - ' . $rechargeRequest->payment_method . ' - From agency account: ' . $agencyBankAccount,
            ]);
        });

        if ($rechargeRequest->artist && $rechargeRequest->artist->user) {
            NotificationService::send(
                $rechargeRequest->artist->user,
                'Wallet recharge approved',
                'We credited ' . number_format($rechargeRequest->amount, 2, '.', ' ') . ' DA to your wallet.',
                [
                    'type' => 'wallet_recharge_approved',
                    'wallet_recharge_id' => $rechargeRequest->id,
                    'link' => route('artist.wallet'),
                ]
            );
        }

        return redirect()->route('gestionnaire.wallet-recharge.index')
            ->with('success', 'Recharge request approved and wallet credited successfully.');
    }

    public function reject(Request $request, $id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $rechargeRequest = WalletRechargeRequest::with('artist')
            ->whereHas('artist', function($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->findOrFail($id);
        $rechargeRequest->loadMissing('artist.user');

        if (!$rechargeRequest->canBeRejected()) {
            return redirect()->back()->withErrors(['request' => 'This request cannot be rejected.']);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $rechargeRequest->status = 'REJECTED';
        $rechargeRequest->approved_by = $gestionnaire->id;
        $rechargeRequest->rejection_reason = $request->rejection_reason;
        $rechargeRequest->save();

        if ($rechargeRequest->artist && $rechargeRequest->artist->user) {
            NotificationService::send(
                $rechargeRequest->artist->user,
                'Wallet recharge rejected',
                'Your recharge request was rejected. Reason: ' . $request->rejection_reason,
                [
                    'type' => 'wallet_recharge_rejected',
                    'wallet_recharge_id' => $rechargeRequest->id,
                    'link' => route('artist.wallet'),
                ]
            );
        }

        return redirect()->route('gestionnaire.wallet-recharge.index')
            ->with('success', 'Recharge request rejected.');
    }
}

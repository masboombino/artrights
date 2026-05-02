<?php

namespace App\Http\Controllers\Artist;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Complain;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\WalletRechargeRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ArtistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:artist');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $artist = $user->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $agency = $artist->agency;

        $artworksCount = Artwork::where('artist_id', $artist->id)->count();
        $pendingArtworksCount = Artwork::where('artist_id', $artist->id)->where('status', 'PENDING')->count();
        $approvedArtworksCount = Artwork::where('artist_id', $artist->id)->where('status', 'APPROVED')->count();
        $rejectedArtworksCount = Artwork::where('artist_id', $artist->id)->where('status', 'REJECTED')->count();
        // Live artworks: approved and platform tax paid
        $liveArtworksCount = Artwork::where('artist_id', $artist->id)
            ->where('status', 'APPROVED')
            ->where('platform_tax_status', 'PAID')
            ->count();
        // Pending payment: approved but platform tax not paid
        $pendingPaymentCount = Artwork::where('artist_id', $artist->id)
            ->where('status', 'APPROVED')
            ->where('platform_tax_status', 'PENDING')
            ->count();
        
        $wallet = Wallet::where('artist_id', $artist->id)->first();
        $balance = $wallet ? $wallet->balance : 0;
        
        $complaintsCount = Complain::where('artist_id', $artist->id)->count();
        
        return view('blades.artist.dashboard', compact('user', 'agency', 'artworksCount', 'pendingArtworksCount', 'approvedArtworksCount', 'rejectedArtworksCount', 'liveArtworksCount', 'pendingPaymentCount', 'balance', 'complaintsCount'));
    }

    public function profile()
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $artist->load('agency');
        return view('blades.artist.profile', compact('artist'));
    }

    public function updateProfile(Request $request)
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $artist->user->id,
            'stage_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'bank_account_number' => 'required|string|max:255',
            'full_name_on_account' => 'required|string|max:255',
            'bank_account_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $artist->update([
            'stage_name' => $request->stage_name,
            'address' => $request->address,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'bank_account_number' => $request->bank_account_number,
            'full_name_on_account' => $request->full_name_on_account,
        ]);

        if ($request->hasFile('bank_account_proof')) {
            if ($artist->bank_account_proof) {
                Storage::disk('public')->delete($artist->bank_account_proof);
            }
            $artist->bank_account_proof = $request->file('bank_account_proof')->store('bank_account_proofs', 'public');
            $artist->save();
        }

        $user = $artist->user;
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->phone) {
            $user->phone = $request->phone;
        }
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $request->file('profile_photo')->store('profile_photos', 'public');
        }
        $user->save();

        return redirect()->route('artist.profile')->with('success', 'Profile updated successfully');
    }

    public function wallet()
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $wallet = Wallet::firstOrCreate(['artist_id' => $artist->id], ['balance' => 0]);
        $transactions = Transaction::where('artist_id', $artist->id)
            ->with(['pv', 'artwork'])
            ->orderBy('created_at', 'desc')
            ->get();
        $pendingRequests = WalletRechargeRequest::where('artist_id', $artist->id)
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('blades.artist.wallet', compact('wallet', 'transactions', 'pendingRequests'));
    }

    public function rechargeWallet(Request $request)
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        // Check if artist has complete bank account profile
        if (
            empty($artist->bank_account_number)
            || empty($artist->full_name_on_account)
            || empty($artist->bank_account_proof)
        ) {
            return redirect()->route('artist.profile')
                ->with('error', 'You must complete your bank profile before recharging your wallet (account number, full name on account, and proof document).');
        }

        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|in:CHEQUE,POSTAL_TRANSFER',
            'transaction_reference' => 'required_if:payment_method,POSTAL_TRANSFER|nullable|string|max:255',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Upload payment proof
        $proofPath = $request->file('payment_proof')->store('wallet_recharge_proofs', 'public');

        $rechargeRequest = WalletRechargeRequest::create([
            'artist_id' => $artist->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_reference' => $request->transaction_reference ?? null,
            'bank_name' => null,
            'account_number' => null,
            'payment_proof_path' => $proofPath,
            'notes' => $request->notes,
            'status' => 'PENDING',
        ]);

        // Send notification to gestionnaire
        if ($artist->agency_id) {
            NotificationService::sendToAgencyRole(
                'gestionnaire',
                $artist->agency_id,
                'New wallet recharge request',
                Auth::user()->name . ' submitted a ' . number_format($request->amount, 2, '.', ' ') . ' DZD recharge request. Please review and approve.',
                [
                    'type' => 'wallet_recharge_request',
                    'wallet_recharge_id' => $rechargeRequest->id,
                    'link' => route('gestionnaire.wallet-recharge.show', $rechargeRequest->id),
                ]
            );
        }

        return redirect()->route('artist.wallet')->with('success', 'Recharge request submitted successfully. Your request has been sent and is pending approval. You will be notified once it is reviewed.');
    }

    public function artworks()
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $artworks = Artwork::where('artist_id', $artist->id)->with('category')->orderBy('created_at', 'desc')->get();

        return view('blades.artist.artworks.all', compact('artworks'));
    }

    public function liveArtworks()
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $artworks = Artwork::where('artist_id', $artist->id)
            ->where('status', 'APPROVED')
            ->where('platform_tax_status', 'PAID')
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('blades.artist.artworks.live', compact('artworks'));
    }

    public function pendingArtworks()
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $artworks = Artwork::where('artist_id', $artist->id)
            ->where('status', 'PENDING')
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('blades.artist.artworks.pending', compact('artworks'));
    }

    public function rejectedArtworks()
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $artworks = Artwork::where('artist_id', $artist->id)
            ->where('status', 'REJECTED')
            ->with('category')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('blades.artist.artworks.rejected', compact('artworks'));
    }

    public function pendingPaymentArtworks()
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $artworks = Artwork::where('artist_id', $artist->id)
            ->where('status', 'APPROVED')
            ->where('platform_tax_status', 'PENDING')
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('blades.artist.artworks.pending-payment', compact('artworks'));
    }

    public function createArtwork()
    {
        $categories = Category::all();
        return view('blades.artist.artworks.create', compact('categories'));
    }

    public function storeArtwork(Request $request)
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp3,mp4',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('artworks', 'public');
        }

        $category = Category::findOrFail($request->category_id);
        
        $artwork = Artwork::create([
            'artist_id' => $artist->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'status' => 'PENDING',
            'platform_tax_status' => 'PENDING',
            'platform_tax_amount' => Artwork::calculatePlatformTax($category),
        ]);

        NotificationService::sendToAgencyRole(
            'gestionnaire',
            $artist->agency_id,
            'New artwork submitted',
            Auth::user()->name . ' uploaded "' . $request->title . '" for review.',
            [
                'type' => 'artwork_submitted',
                'artwork_id' => $artwork->id,
                'link' => route('gestionnaire.show-artwork', $artwork->id),
            ]
        );

        return redirect()->route('artist.artworks.pending')->with('success', 'Artwork created successfully and pending approval');
    }

    public function editArtwork($id)
    {
        $artist = Auth::user()->artist;
        $artwork = Artwork::where('artist_id', $artist->id)->findOrFail($id);
        $categories = Category::all();

        return view('blades.artist.artworks.edit', compact('artwork', 'categories'));
    }

    public function updateArtwork(Request $request, $id)
    {
        $artist = Auth::user()->artist;
        $artwork = Artwork::where('artist_id', $artist->id)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp3,mp4',
        ]);

        if ($request->hasFile('file')) {
            if ($artwork->file_path) {
                Storage::disk('public')->delete($artwork->file_path);
            }
            $artwork->file_path = $request->file('file')->store('artworks', 'public');
        }

        // Update artwork without changing category or requiring re-payment
        $artwork->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'PENDING', // Reset to pending for gestionnaire approval
        ]);

        return redirect()->route('artist.artworks.pending')->with('success', 'Artwork updated successfully');
    }

    public function deleteArtwork($id)
    {
        $artist = Auth::user()->artist;
        $artwork = Artwork::where('artist_id', $artist->id)->findOrFail($id);

        if ($artwork->file_path) {
            Storage::disk('public')->delete($artwork->file_path);
        }

        $artwork->delete();

        return redirect()->back()->with('success', 'Artwork deleted successfully');
    }

    // Reports and Complaints System (Artists can only send complaints)
    public function complaints(Request $request)
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }
        
        // Get all complaints sent by this artist
        $complaintsQuery = Complain::with(['admin', 'gestionnaire', 'targetUser', 'sender', 'agency'])
            ->where('artist_id', $artist->id)
            ->where('type', Complain::TYPE_COMPLAINT)
            ->notHiddenBy(Auth::id());
        
        if ($request->filled('status')) {
            $complaintsQuery->where('status', $request->status);
        }
        
        $complaints = $complaintsQuery->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        // Get reports (empty for artists, but keeping structure consistent)
        $reports = collect([]);
        
        $stats = [
            'complaints_total' => Complain::where('artist_id', $artist->id)->where('type', Complain::TYPE_COMPLAINT)->count(),
            'complaints_pending' => Complain::where('artist_id', $artist->id)->where('type', Complain::TYPE_COMPLAINT)->where('status', 'PENDING')->count(),
            'reports_total' => 0,
            'reports_pending' => 0,
        ];
        
        return view('blades.artist.reports-and-complaints.index', compact('complaints', 'reports', 'stats', 'artist'));
    }

    public function complaintsInbox(Request $request)
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }
        
        $query = Complain::with(['sender', 'admin', 'gestionnaire'])
            ->where('target_role', 'artist')
            ->where('type', Complain::TYPE_COMPLAINT)
            ->notHiddenBy(Auth::id())
            ->where(function($q) use ($artist) {
                $q->where('target_user_id', Auth::id())
                  ->orWhere('artist_id', $artist->id);
            });
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $complaints = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        return view('blades.artist.complaints.inbox', compact('complaints'));
    }

    public function complaintsSent(Request $request)
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }
        
        $query = Complain::with(['targetUser', 'admin', 'gestionnaire'])
            ->where('artist_id', $artist->id)
            ->where('type', Complain::TYPE_COMPLAINT)
            ->where('sender_user_id', Auth::id())
            ->notHiddenBy(Auth::id());
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $complaints = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        return view('blades.artist.complaints.sent', compact('complaints'));
    }

    public function createComplaint()
    {
        $artist = Auth::user()->artist;

        if (!$artist || !$artist->agency_id) {
            return redirect()->route('artist.complaints.index')->with('error', 'You must be associated with an agency to submit complaints.');
        }

        // Get all agencies for selection
        $agencies = \App\Models\Agency::orderBy('wilaya')->orderBy('agency_name')->get();

        return view('blades.artist.reports-and-complaints.create', compact('agencies'));
    }

    public function getAgencyOfficials($agencyId)
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return response()->json(['error' => 'Artist not found'], 403);
        }

        $agency = \App\Models\Agency::with(['admin', 'gestionnaires'])->findOrFail($agencyId);

        $isArtistAgency = $agency->id === $artist->agency_id;

        return response()->json([
            'admin' => $isArtistAgency && $agency->admin ? [
                'id' => $agency->admin->id,
                'name' => $agency->admin->name,
            ] : null,
            'gestionnaires' => $agency->gestionnaires->map(function($gestionnaire) {
                return [
                    'id' => $gestionnaire->id,
                    'name' => $gestionnaire->name,
                ];
            })->toArray(),
            'is_artist_agency' => $isArtistAgency
        ]);
    }

    public function showComplaint($id)
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }
        
        $complaint = Complain::with(['admin', 'gestionnaire', 'targetUser', 'sender'])
            ->where('artist_id', $artist->id)
            ->where('type', Complain::TYPE_COMPLAINT)
            ->find($id);
        
        if (!$complaint) {
            return view('blades.shared.content-not-found', [
                'pageTitle' => 'Complaint Not Found',
                'title' => 'Complaint Not Found',
                'message' => 'Sorry, the complaint you are looking for does not exist or has been deleted.',
                'backUrl' => route('artist.complaints.index'),
            ]);
        }
        
        return view('blades.artist.reports-and-complaints.show', compact('complaint'));
    }

    public function deleteComplaint($id)
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }
        
        // Artist can delete any complaint they see in their table (complaints only, not reports)
        $complaint = Complain::where('type', Complain::TYPE_COMPLAINT)
            ->findOrFail($id);
        
        $complaint->hideForUser(Auth::id());
        
        return redirect()->route('artist.complaints.index')->with('success', 'Complaint deleted successfully');
    }

    public function storeComplaint(Request $request)
    {
        $artist = Auth::user()->artist;

        if (!$artist || !$artist->agency_id) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'target_role' => 'required|in:admin,gestionnaire',
            'target_user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'location_link' => 'nullable|url|max:255',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ], [
            'images.*.max' => 'Each image must not be larger than 10MB.',
            'images.*.image' => 'Each file must be an image.',
        ]);

        // Validate that artist can only send to admin in their own agency
        if ($data['target_role'] === 'admin' && $data['agency_id'] !== $artist->agency_id) {
            return redirect()->back()->withErrors(['target_role' => 'You can only send complaints to admins in your own agency.'])->withInput();
        }

        // Check if agency has an admin
        $selectedAgency = \App\Models\Agency::find($data['agency_id']);
        if ($data['target_role'] === 'admin' && (!$selectedAgency || !$selectedAgency->admin_id)) {
            return redirect()->back()
                ->withErrors(['target_role' => 'This agency currently has no admin assigned. Please try again later or contact support.'])
                ->withInput();
        }

        $images = [];
        if ($request->hasFile('images')) {
            $uploadedImages = $request->file('images');
            if (count($uploadedImages) > 5) {
                return redirect()->back()->withErrors(['images' => 'You can upload maximum 5 images.'])->withInput();
            }

            foreach ($uploadedImages as $image) {
                $images[] = $image->store('complaints', 'public');
            }
        }

        $targetRole = $data['target_role'];
        $selectedAgencyId = $data['agency_id'];
        $targetUserId = $data['target_user_id'];

        $complaintType = Complain::resolveType('artist', $targetRole);

        // Get the selected agency
        $selectedAgency = \App\Models\Agency::find($selectedAgencyId);

        $complaint = Complain::create([
            'type' => Complain::TYPE_COMPLAINT,
            'complaint_type' => $complaintType,
            'artist_id' => $artist->id,
            'agency_id' => $selectedAgencyId, // Use selected agency instead of artist's agency
            'sender_user_id' => Auth::id(),
            'sender_role' => 'artist',
            'target_role' => $targetRole,
            'target_user_id' => $targetUserId,
            'gestionnaire_id' => $targetRole === 'gestionnaire' ? $targetUserId : null,
            'admin_id' => $targetRole === 'admin' ? $targetUserId : null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'location_link' => $data['location_link'] ?? null,
            'images' => !empty($images) ? $images : null,
            'status' => 'PENDING',
        ]);

        $notificationLink = $targetRole === 'gestionnaire'
            ? route('gestionnaire.reports-and-complaints.index')
            : route('admin.reports-and-complaints.index');

        NotificationService::sendToAgencyRole(
            $targetRole,
            $selectedAgencyId, // Send notification to selected agency
            'New complaint from ' . Auth::user()->name . ' (regarding ' . $selectedAgency->agency_name . ')',
            'Subject: ' . $data['subject'],
            [
                'type' => 'complaint_created',
                'complaint_id' => $complaint->id,
                'link' => $notificationLink,
            ]
        );

        return redirect()->route('artist.complaints.index')->with('success', 'Complaint submitted successfully');
    }

    public function showArtwork($id)
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $artwork = Artwork::where('artist_id', $artist->id)->with('category')->find($id);
        
        if (!$artwork) {
            return view('blades.shared.content-not-found', [
                'pageTitle' => 'Artwork Not Found',
                'title' => 'Artwork Not Found',
                'message' => 'Sorry, the artwork you are looking for does not exist or has been deleted.',
                'backUrl' => route('artist.artworks'),
            ]);
        }
        
        $wallet = Wallet::firstOrCreate(['artist_id' => $artist->id], ['balance' => 0]);

        return view('blades.artist.artworks.show', compact('artwork', 'wallet'));
    }


    public function payPlatformTax($id)
    {
        $artist = Auth::user()->artist;
        
        if (!$artist) {
            return redirect()->route('login');
        }

        $artwork = Artwork::where('artist_id', $artist->id)->findOrFail($id);

        if ($artwork->platform_tax_status === 'PAID') {
            return redirect()->back()->with('error', 'Platform tax already paid for this artwork.');
        }

        if ($artwork->status !== 'APPROVED') {
            return redirect()->back()->with('error', 'Artwork must be approved before paying tax.');
        }

        $wallet = Wallet::firstOrCreate(['artist_id' => $artist->id], ['balance' => 0]);
        $taxAmount = $artwork->platform_tax_amount ?? config('artrights.platform_tax_amount', 500);

        if ($wallet->balance < $taxAmount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance. Please recharge your wallet.');
        }

        \DB::transaction(function () use ($wallet, $artwork, $taxAmount, $artist) {
            $agency = $artist->agency;
            $agencyBankAccount = $agency && $agency->bank_account_number ? $agency->bank_account_number : 'N/A';
            
            // Deduct from artist wallet
            $wallet->balance -= $taxAmount;
            $wallet->last_transaction = now();
            $wallet->save();

            // Add to agency wallet
            $agencyWallet = \App\Models\AgencyWallet::firstOrCreate(
                ['agency_id' => $artist->agency_id],
                ['balance' => 0]
            );
            $agencyWallet->balance += $taxAmount;
            $agencyWallet->last_transaction = now();
            $agencyWallet->save();

            // Record artist transaction (outgoing)
            Transaction::create([
                'artist_id' => $artist->id,
                'artwork_id' => $artwork->id,
                'type' => 'PLATFORM_TAX',
                'amount' => -$taxAmount,
                'payment_method' => 'WALLET_RECHARGE',
                'payment_status' => 'VALIDATED',
                'description' => 'Platform tax payment for artwork: ' . $artwork->title . ' - To agency account: ' . $agencyBankAccount,
            ]);

            // Record agency wallet transaction (incoming)
            \App\Models\AgencyWalletTransaction::create([
                'agency_wallet_id' => $agencyWallet->id,
                'direction' => 'IN',
                'amount' => $taxAmount,
                'description' => 'Platform tax payment from artist: ' . Auth::user()->name . ' for artwork: ' . $artwork->title . ' - To agency account: ' . $agencyBankAccount,
            ]);

            $artwork->platform_tax_status = 'PAID';
            $artwork->platform_tax_paid_at = now();
            $artwork->save();
        });

        NotificationService::sendToAgencyRole(
            'gestionnaire',
            $artist->agency_id,
            'Platform tax paid',
            Auth::user()->name . ' paid ' . number_format($taxAmount, 2, '.', ' ') . ' DA for "' . $artwork->title . '".',
            [
                'type' => 'platform_tax_paid',
                'artwork_id' => $artwork->id,
                'link' => route('gestionnaire.show-artwork', $artwork->id),
            ]
        );

        NotificationService::send(
            Auth::user(),
            'Artwork activated',
            'Great! "' . $artwork->title . '" is now active on the platform.',
            [
                'type' => 'artwork_activated',
                'artwork_id' => $artwork->id,
                'link' => route('artist.show-artwork', $artwork->id),
            ]
        );

        return redirect()->back()->with('success', 'Platform tax paid successfully. Your artwork is now active!');
    }
}


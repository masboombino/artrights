<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\User\AccountApprovedEmail;
use App\Mail\User\AccountRejectedEmail;
use App\Models\User;
use App\Models\Agent;
use App\Models\AgencyWallet;
use App\Models\AgencyWalletTransaction;
use App\Models\Artist;
use App\Models\Complain;
use App\Models\Mission;
use App\Models\PV;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Services\NotificationService;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function dashboard()
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;
        $agency = $admin->agency;

        // Only show artists from admin's agency (must have agency_id)
        $pendingArtistsCount = Artist::where('status', 'PENDING_VALIDATION')
            ->whereNotNull('agency_id')
            ->where('agency_id', $agencyId)
            ->count();
        
        $complaintsCount = Complain::where('status', 'PENDING')
            ->where('target_role', 'admin')
            ->where('type', Complain::TYPE_COMPLAINT)
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            })
            ->count();
        
        $pendingReportsCount = Complain::where('status', 'PENDING')
            ->where('target_role', 'admin')
            ->where('type', Complain::TYPE_REPORT)
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            })
            ->count();
        
        // Only show gestionnaires from admin's agency (must have agency_id)
        $gestionnairesCount = User::whereHas('roles', function($q) { 
                $q->where('name', 'gestionnaire'); 
            })
            ->whereNotNull('agency_id')
            ->where('agency_id', $agencyId)
            ->count();

        $missionAssigned = Mission::where('agency_id', $agencyId)->where('status', 'ASSIGNED')->count();
        $missionInProgress = Mission::where('agency_id', $agencyId)->where('status', 'IN_PROGRESS')->count();
        $allPvsCount = PV::where('agency_id', $agencyId)->count();
        $agentsCount = Agent::where('agency_id', $agencyId)->count();
        $wallet = AgencyWallet::firstOrCreate(
            ['agency_id' => $agencyId],
            ['balance' => 0]
        );
        
        // Count all artist transactions for this agency
        $artistTransactionsCount = Transaction::whereHas('artist', function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->count();
        
        return view('blades.admin.dashboard', compact(
            'admin',
            'agency',
            'pendingArtistsCount',
            'pendingReportsCount',
            'complaintsCount',
            'gestionnairesCount',
            'missionAssigned',
            'missionInProgress',
            'allPvsCount',
            'agentsCount',
            'wallet',
            'artistTransactionsCount'
        ));
    }

    public function manageUsers()
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        // Only show artists from admin's agency (must have agency_id and user)
        $pendingArtists = Artist::where('status', 'PENDING_VALIDATION')
            ->whereNotNull('agency_id')
            ->where('agency_id', $agencyId)
            ->whereHas('user')
            ->with(['user', 'agency'])
            ->get();
        
        $approvedArtists = Artist::where('status', 'APPROVED')
            ->whereNotNull('agency_id')
            ->where('agency_id', $agencyId)
            ->whereHas('user')
            ->with(['user', 'agency'])
            ->get();
        
        $rejectedArtists = Artist::where('status', 'REJECTED')
            ->whereNotNull('agency_id')
            ->where('agency_id', $agencyId)
            ->whereHas('user')
            ->with(['user', 'agency'])
            ->get();
        
        return view('blades.admin.manage-users', compact('pendingArtists', 'approvedArtists', 'rejectedArtists'));
    }

    public function viewArtist($id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $artist = Artist::whereNotNull('agency_id')
            ->where('agency_id', $agencyId)
            ->with(['user', 'agency'])
            ->findOrFail($id);
        
        return view('blades.admin.view-artist', compact('artist'));
    }

    public function approveArtist($id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $artist = Artist::whereNotNull('agency_id')
            ->where('agency_id', $agencyId)
            ->findOrFail($id);
        
        $artist->status = 'APPROVED';
        $artist->save();

        $user = $artist->user;
        $user->agency_id = $artist->agency_id;
        $user->save();
        $user->assignRole('artist');

        // Create wallet for the artist if it doesn't exist
        $wallet = Wallet::firstOrCreate(
            ['artist_id' => $artist->id],
            ['balance' => 0]
        );

        if (!$artist->wallet_id) {
            $artist->wallet_id = $wallet->id;
            $artist->save();
        }

        if ($artist->user) {
            NotificationService::send(
                $artist->user,
                'Account approved',
                'Your artist profile has been approved. You can now access your dashboard and upload artworks.',
                [
                    'type' => 'artist_account_approved',
                    'artist_id' => $artist->id,
                    'link' => route('artist.dashboard'),
                ]
            );

            // Send approval email to user
            Mail::to($artist->user->email)->send(new AccountApprovedEmail($artist->user));
        }

        return redirect()->route('admin.manage-users')->with('success', 'Artist approved successfully');
    }

    public function rejectArtist(Request $request, $id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000',
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection.',
            'rejection_reason.min' => 'The rejection reason must be at least 10 characters.',
            'rejection_reason.max' => 'The rejection reason must not exceed 1000 characters.',
        ]);

        $artist = Artist::whereNotNull('agency_id')
            ->where('agency_id', $agencyId)
            ->findOrFail($id);
        
        // Get user before deleting artist
        $user = $artist->user;
        $rejectionReason = $request->input('rejection_reason');
        
        // Send rejection email before deleting the account
        if ($user) {
            Mail::to($user->email)->send(new AccountRejectedEmail($user, $rejectionReason));
        }
        
        // Use database transaction to ensure complete deletion
        DB::transaction(function () use ($artist, $user) {
            // Delete identity document file if exists
            if ($artist->identity_document) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($artist->identity_document);
            }
            
            // Delete wallet if exists
            if ($artist->wallet_id) {
                Wallet::where('id', $artist->wallet_id)->delete();
            }
            
            // Hard delete the artist first (permanently delete from database)
            $artist->forceDelete();
            
            // Then hard delete the user (permanently delete from database)
            if ($user) {
                $user->forceDelete();
            }
        });

        return redirect()->route('admin.manage-users')->with('success', 'Artist rejected and account permanently deleted');
    }

    // Reports and Complaints System
    public function complaints(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;
        $type = $request->get('type', 'all'); // all, complaint, or report

        // Get all items (complaints and reports) sent to admin
        $itemsQuery = Complain::with([
                'artist.user',
                'artist.agency',
                'agentProfile.user',
                'sender',
                'agency',
                'gestionnaire',
                'targetUser',
            ])
            ->where('target_role', 'admin')
            ->notHiddenBy(Auth::id())
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            });

        // Filter by type
        if ($type === 'complaint') {
            $itemsQuery->where('type', Complain::TYPE_COMPLAINT);
        } elseif ($type === 'report') {
            $itemsQuery->where('type', Complain::TYPE_REPORT);
        }

        // Filter by status
        if ($request->filled('status')) {
            $itemsQuery->where('status', $request->status);
        }

        $items = $itemsQuery->orderByDesc('created_at')->paginate(10)->withQueryString();

        // Keep separate queries for stats
        $complaintsQuery = Complain::with([
                'artist.user',
                'artist.agency',
                'agentProfile.user',
                'sender',
                'agency',
                'gestionnaire',
                'targetUser',
            ])
            ->where('target_role', 'admin')
            ->where('type', Complain::TYPE_COMPLAINT)
            ->notHiddenBy(Auth::id())
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            });

        if ($request->filled('status')) {
            $complaintsQuery->where('status', $request->status);
        }

        $complaints = $complaintsQuery->orderByDesc('created_at')->paginate(20)->withQueryString();

        $reportsQuery = Complain::with([
                'artist.user',
                'artist.agency',
                'agentProfile.user',
                'sender',
                'agency',
                'gestionnaire',
                'targetUser',
            ])
            ->where('target_role', 'admin')
            ->where('type', Complain::TYPE_REPORT)
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            });

        if ($request->filled('status')) {
            $reportsQuery->where('status', $request->status);
        }

        $reports = $reportsQuery->orderByDesc('created_at')->paginate(20)->withQueryString();

        $stats = [
            'complaints_total' => Complain::complaints()
                ->where('target_role', 'admin')
                ->where(function ($q) use ($agencyId, $admin) {
                    $q->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
                })
                ->count(),
            'complaints_pending' => Complain::complaints()
                ->where('target_role', 'admin')
                ->where('status', 'PENDING')
                ->where(function ($q) use ($agencyId, $admin) {
                    $q->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
                })
                ->count(),
            'reports_total' => Complain::reports()
                ->where('target_role', 'admin')
                ->where(function ($q) use ($agencyId, $admin) {
                    $q->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
                })
                ->count(),
            'reports_pending' => Complain::reports()
                ->where('target_role', 'admin')
                ->where('status', 'PENDING')
                ->where(function ($q) use ($agencyId, $admin) {
                    $q->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
                })
                ->count(),
        ];
        
        // Get gestionnaires for forwarding
        $gestionnaires = \App\Models\User::whereHas('roles', function($q) {
                $q->where('name', 'gestionnaire');
            })
            ->where('agency_id', $agencyId)
            ->get();
        
        return view('blades.admin.reports-and-complaints.index', compact('items', 'complaints', 'reports', 'admin', 'stats', 'gestionnaires'));
    }

    public function complaintsInbox(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $query = Complain::with(['sender', 'artist.user', 'agentProfile.user', 'gestionnaire'])
            ->where('target_role', 'admin')
            ->where('type', Complain::TYPE_COMPLAINT)
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.admin.complaints.inbox', compact('items', 'admin'));
    }

    public function complaintsSent(Request $request)
    {
        $admin = auth()->user();

        $query = Complain::with(['targetUser', 'gestionnaire', 'agency'])
            ->where('sender_user_id', $admin->id)
            ->where('type', Complain::TYPE_COMPLAINT);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.admin.complaints.sent', compact('items', 'admin'));
    }

    public function createComplaint()
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;
        
        // Get gestionnaires from same agency
        $gestionnaires = \App\Models\User::whereHas('roles', function($q) {
                $q->where('name', 'gestionnaire');
            })
            ->where('agency_id', $agencyId)
            ->get();
        
        $type = request()->get('type', 'complaint'); // complaint or report
        
        return view('blades.admin.reports-and-complaints.create', compact('gestionnaires', 'type', 'admin'));
    }

    public function storeComplaint(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $type = $request->get('type', 'complaint'); // complaint or report
        
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'location_link' => 'nullable|url|max:255',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png',
            'files.*' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
            'target_role' => 'required|in:super_admin,gestionnaire',
            'target_user_id' => 'nullable|exists:users,id',
        ], [
            'images.*.max' => 'Each image must not be larger than 10MB.',
            'images.*.image' => 'Each file must be an image.',
            'files.*.max' => 'Each file must not be larger than 10MB.',
            'files.*.mimes' => 'Files must be PDF, DOC, DOCX, XLS, or XLSX.',
        ]);

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

        $files = [];
        if ($request->hasFile('files') && $type === 'report') {
            $uploadedFiles = $request->file('files');
            foreach ($uploadedFiles as $file) {
                $files[] = $file->store('reports', 'public');
            }
        }

        $targetRole = $data['target_role'];
        $targetUserId = $data['target_user_id'] ?? null;
        $complaintType = Complain::resolveType('admin', $targetRole);
        
        // Determine target user - Super Admin is automatic (only one exists)
        if ($targetRole === 'super_admin') {
            $superAdmin = \App\Models\User::whereHas('roles', function($q) {
                    $q->where('name', 'super_admin');
                })->first();
            $targetUserId = $superAdmin ? $superAdmin->id : null;
        } elseif ($targetRole === 'gestionnaire' && !$targetUserId) {
            $gestionnaire = \App\Models\User::whereHas('roles', function($q) {
                    $q->where('name', 'gestionnaire');
                })
                ->where('agency_id', $agencyId)
                ->first();
            $targetUserId = $gestionnaire ? $gestionnaire->id : null;
        }
        
        $complaint = Complain::create([
            'type' => $type === 'report' ? Complain::TYPE_REPORT : Complain::TYPE_COMPLAINT,
            'complaint_type' => $complaintType,
            'admin_id' => $admin->id,
            'super_admin_id' => $targetRole === 'super_admin' ? $targetUserId : null,
            'gestionnaire_id' => $targetRole === 'gestionnaire' ? $targetUserId : null,
            'agency_id' => $agencyId,
            'sender_user_id' => $admin->id,
            'sender_role' => 'admin',
            'target_role' => $targetRole,
            'target_user_id' => $targetUserId,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'location_link' => $data['location_link'] ?? null,
            'images' => !empty($images) ? $images : null,
            'files' => !empty($files) ? $files : null,
            'status' => 'PENDING',
        ]);

        $notificationLink = $targetRole === 'super_admin'
            ? route('superadmin.complaints.index')
            : route('gestionnaire.reports-and-complaints.index');

        if ($targetRole === 'super_admin') {
            $targetUser = \App\Models\User::find($targetUserId);
            if ($targetUser) {
                NotificationService::send(
                    $targetUser,
                    'New ' . ($type === 'report' ? 'report' : 'complaint') . ' from ' . $admin->name,
                    'Subject: ' . $data['subject'],
                    [
                        'type' => $type === 'report' ? 'report_created' : 'complaint_created',
                        'complaint_id' => $complaint->id,
                        'link' => $notificationLink,
                    ]
                );
            }
        } else {
            NotificationService::sendToAgencyRole(
                $targetRole,
                $agencyId,
                'New ' . ($type === 'report' ? 'report' : 'complaint') . ' from ' . $admin->name,
                'Subject: ' . $data['subject'],
                [
                    'type' => $type === 'report' ? 'report_created' : 'complaint_created',
                    'complaint_id' => $complaint->id,
                    'link' => $notificationLink,
                ]
            );
        }

        return redirect()->route('admin.complaints.index')
            ->with('success', ucfirst($type) . ' submitted successfully.');
    }

    // Reports System
    public function reports(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $query = Complain::with([
                'artist.user',
                'artist.agency',
                'agentProfile.user',
                'sender',
                'agency',
                'gestionnaire',
                'targetUser',
            ])
            ->where('target_role', 'admin')
            ->where('type', Complain::TYPE_REPORT)
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $stats = [
            'total' => Complain::reports()
                ->where('target_role', 'admin')
                ->where(function ($q) use ($agencyId, $admin) {
                    $q->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
                })
                ->count(),
            'pending' => Complain::reports()
                ->where('target_role', 'admin')
                ->where('status', 'PENDING')
                ->where(function ($q) use ($agencyId, $admin) {
                    $q->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
                })
                ->count(),
        ];
        
        return view('blades.admin.reports.index', compact('items', 'admin', 'stats'));
    }

    public function reportsInbox(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $query = Complain::with(['sender', 'artist.user', 'agentProfile.user', 'gestionnaire'])
            ->where('target_role', 'admin')
            ->where('type', Complain::TYPE_REPORT)
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.admin.reports.inbox', compact('items', 'admin'));
    }

    public function reportsSent(Request $request)
    {
        $admin = auth()->user();

        $query = Complain::with(['targetUser', 'gestionnaire', 'agency'])
            ->where('sender_user_id', $admin->id)
            ->where('type', Complain::TYPE_REPORT);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.admin.reports.sent', compact('items', 'admin'));
    }

    public function createReport()
    {
        return $this->createComplaint(); // Same view, different type
    }

    public function storeReport(Request $request)
    {
        $request->merge(['type' => 'report']);
        return $this->storeComplaint($request);
    }

    public function resolveComplaint($id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $complaint = Complain::where('target_role', 'admin')
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            })
            ->findOrFail($id);
        
        $complaint->status = 'RESOLVED';
        $complaint->admin_id = $admin->id;
        $complaint->save();

        if ($complaint->sender) {
            NotificationService::send(
                $complaint->sender,
                'Complaint resolved',
                'Your complaint "' . $complaint->subject . '" was marked as resolved.',
                [
                    'type' => 'complaint_resolved',
                    'complaint_id' => $complaint->id,
                    'link' => $this->complaintSenderLink($complaint),
                ]
            );
        }

        return redirect()->route('admin.reports-and-complaints.index')->with('success', 'Complaint resolved successfully');
    }

    public function managePVs(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $query = PV::with(['agent.user', 'mission', 'finalizedBy'])
            ->where('agency_id', $agencyId);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('finalized')) {
            if ($request->finalized === 'yes') {
                $query->whereNotNull('finalized_at');
            } else {
                $query->whereNull('finalized_at');
            }
        }

        $pvs = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('blades.admin.manage-pvs', compact('pvs'));
    }

    public function financialTransactions()
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $wallet = AgencyWallet::where('agency_id', $agencyId)->first();
        
        $transactions = AgencyWalletTransaction::with('pv')
            ->whereHas('wallet', function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('blades.admin.financial-transactions', compact('wallet', 'transactions'));
    }

    public function viewPV($id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $pv = PV::with([
                'agent.user',
                'mission',
                'devices',
                'artworkUsages.artwork.artist.user',
            ])
            ->where('agency_id', $agencyId)
            ->findOrFail($id);

        return view('blades.admin.view-pv', compact('pv'));
    }


    public function manageGestionnaires()
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $gestionnaires = User::whereHas('roles', function($q) { 
                $q->where('name', 'gestionnaire'); 
            })
            ->whereNotNull('agency_id')
            ->where('agency_id', $agencyId)
            ->with('agency')
            ->get();
        
        return view('blades.admin.manage-gestionnaires', compact('gestionnaires'));
    }

    public function createGestionnaire()
    {
        return view('blades.admin.create-gestionnaire');
    }

    public function storeGestionnaire(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
        ]);

        $gestionnaire = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'agency_id' => $agencyId, // Assign same agency as admin
            'role_id' => Role::where('name', 'gestionnaire')->first()->id,
        ]);

        $gestionnaire->assignRole('gestionnaire');

        return redirect()->route('admin.manage-gestionnaires')->with('success', 'Gestionnaire created successfully');
    }

    public function removeGestionnaire($id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $gestionnaire = User::whereHas('roles', function($q) {
                $q->where('name', 'gestionnaire');
            })
            ->where('agency_id', $agencyId)
            ->findOrFail($id);

        $gestionnaire->removeRole('gestionnaire');
        $gestionnaire->delete();

        return redirect()->route('admin.manage-gestionnaires')->with('success', 'Gestionnaire removed successfully');
    }

    public function viewComplaint($id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        // Get complaint/report sent to this admin
        $complaint = Complain::with(['artist.user', 'artist.agency', 'agentProfile.user', 'admin', 'gestionnaire', 'sender', 'targetUser'])
            ->where('target_role', 'admin')
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            })
            ->find($id);
        
        if (!$complaint) {
            return view('blades.shared.content-not-found', [
                'pageTitle' => 'Complaint Not Found',
                'title' => 'Complaint Not Found',
                'message' => 'Sorry, the complaint you are looking for does not exist or has been deleted.',
                'backUrl' => route('admin.reports-and-complaints.index'),
            ]);
        }

        $gestionnaires = User::whereHas('roles', function($q) { 
                $q->where('name', 'gestionnaire'); 
            })
            ->where('agency_id', $agencyId)
            ->get();
        
        return view('blades.admin.reports-and-complaints.show', ['complaint' => $complaint, 'gestionnaires' => $gestionnaires, 'admin' => $admin]);
    }

    public function respondToComplaint(Request $request, $id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $request->validate([
            'admin_response' => 'required|string',
            'admin_response_images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ], [
            'admin_response_images.*.max' => 'Each image must not be larger than 10MB.',
            'admin_response_images.*.image' => 'Each file must be an image.',
        ]);

        // Find complaint that belongs to this admin's agency (either from artist or agent)
        $complaint = Complain::where('target_role', 'admin')
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            })
            ->findOrFail($id);
        
        $adminResponseImages = [];
        if ($request->hasFile('admin_response_images')) {
            $uploadedImages = $request->file('admin_response_images');
            if (count($uploadedImages) > 5) {
                return redirect()->back()->withErrors(['admin_response_images' => 'You can upload maximum 5 images.'])->withInput();
            }
            
            foreach ($uploadedImages as $image) {
                $adminResponseImages[] = $image->store('complaint_responses', 'public');
            }
        }

        $complaint->admin_response = $request->admin_response;
        $complaint->admin_response_images = !empty($adminResponseImages) ? $adminResponseImages : null;
        $complaint->admin_id = auth()->id();
        $complaint->status = 'RESOLVED';
        $complaint->responded_at = now();
        $complaint->save();

        // Send notification to the sender (artist or agent)
        $notificationLink = '';
        $notificationMessage = '';

        if ($complaint->artist && $complaint->artist->user) {
            // Complaint from artist
            $notificationLink = route('artist.complaints.index');
            $notificationMessage = 'Admin responded to your complaint "' . $complaint->subject . '".';
            $recipient = $complaint->artist->user;
        } elseif ($complaint->agentProfile && $complaint->agentProfile->user) {
            // Complaint from agent
            $notificationLink = route('agent.complaints.index');
            $notificationMessage = 'Admin responded to your ' . strtolower($complaint->type) . ' "' . $complaint->subject . '".';
            $recipient = $complaint->agentProfile->user;
        }

        if ($recipient) {
            NotificationService::send(
                $recipient,
                ucfirst($complaint->type) . ' answered',
                $notificationMessage,
                [
                    'type' => 'complaint_answered',
                    'complaint_id' => $complaint->id,
                    'link' => $notificationLink,
                ]
            );
        }

        return redirect()->route('admin.reports-and-complaints.index')->with('success', 'Response sent successfully');
    }

    public function forwardToGestionnaire(Request $request, $id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $request->validate([
            'gestionnaire_id' => 'required|exists:users,id',
        ]);

        $gestionnaire = User::whereHas('roles', function ($q) {
                $q->where('name', 'gestionnaire');
            })
            ->where('agency_id', $agencyId)
            ->findOrFail($request->gestionnaire_id);

        $complaint = Complain::where('target_role', 'admin')
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            })
            ->findOrFail($id);

        // Create a new complaint forwarded to gestionnaire
        $forwardedComplaint = Complain::create([
            'type' => $complaint->type,
            'complaint_type' => Complain::TYPE_ADMIN_TO_GESTIONNAIRE,
            'admin_id' => $admin->id,
            'gestionnaire_id' => $gestionnaire->id,
            'agency_id' => $agencyId,
            'artist_id' => $complaint->artist_id,
            'agent_id' => $complaint->agent_id,
            'sender_user_id' => $admin->id,
            'sender_role' => 'admin',
            'target_role' => 'gestionnaire',
            'target_user_id' => $gestionnaire->id,
            'subject' => 'Forwarded: ' . $complaint->subject,
            'message' => $complaint->message,
            'location_link' => $complaint->location_link,
            'images' => $complaint->images,
            'status' => 'PENDING',
        ]);

        // Update original complaint status
        $complaint->status = 'IN_PROGRESS';
        $complaint->save();

        NotificationService::send(
            $gestionnaire,
            'Complaint forwarded to you',
            'Admin forwarded complaint "' . $complaint->subject . '" for you to handle.',
            [
                'type' => 'complaint_forwarded',
                'complaint_id' => $forwardedComplaint->id,
                'link' => route('gestionnaire.reports-and-complaints.show', $forwardedComplaint->id),
            ]
        );

        return redirect()->route('admin.reports-and-complaints.index')->with('success', 'Complaint forwarded to gestionnaire successfully');
    }

    public function assignComplaint(Request $request, $id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $request->validate([
            'gestionnaire_id' => 'required|exists:users,id',
        ]);

        $gestionnaire = User::whereHas('roles', function ($q) {
                $q->where('name', 'gestionnaire');
            })
            ->where('agency_id', $agencyId)
            ->findOrFail($request->gestionnaire_id);

        $complaint = Complain::whereHas('artist', function($query) use ($agencyId) {
                $query->whereNotNull('agency_id')
                      ->where('agency_id', $agencyId);
            })
            ->findOrFail($id);

        $complaint->gestionnaire_id = $gestionnaire->id;
        $complaint->status = 'PENDING';
        $complaint->save();

        NotificationService::send(
            $gestionnaire,
            'Complaint assigned to you',
            'Admin assigned complaint "' . $complaint->subject . '" for follow up.',
            [
                'type' => 'complaint_assigned',
                'complaint_id' => $complaint->id,
                'link' => route('gestionnaire.complaints.show', $complaint->id),
            ]
        );

        if ($complaint->artist && $complaint->artist->user) {
            NotificationService::send(
                $complaint->artist->user,
                'Gestionnaire assigned',
                'Your complaint "' . $complaint->subject . '" is now handled by ' . $gestionnaire->name . '.',
                [
                    'type' => 'complaint_in_progress',
                    'complaint_id' => $complaint->id,
                    'link' => route('artist.complaints.index'),
                ]
            );
        }

        return redirect()->back()->with('success', 'Complaint assigned to gestionnaire.');
    }

    public function createSuperAdminComplaint()
    {
        return view('blades.admin.create-superadmin-complaint');
    }

    public function storeSuperAdminComplaint(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ], [
            'images.*.max' => 'Each image must not be larger than 10MB.',
            'images.*.image' => 'Each file must be an image.',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            $uploadedImages = $request->file('images');
            if (count($uploadedImages) > 5) {
                return redirect()->back()->withErrors(['images' => 'You can upload maximum 5 images.'])->withInput();
            }
            
            foreach ($uploadedImages as $image) {
                $images[] = $image->store('admin_complaints', 'public');
            }
        }

        $complaint = Complain::create([
            'type' => Complain::TYPE_COMPLAINT, // Default to complaint
            'complaint_type' => 'ADMIN_TO_SUPERADMIN',
            'admin_id' => $admin->id,
            'sender_user_id' => $admin->id,
            'sender_role' => 'admin',
            'target_role' => 'super_admin',
            'subject' => $request->subject,
            'message' => $request->message,
            'images' => !empty($images) ? $images : null,
            'status' => 'PENDING',
        ]);

        NotificationService::send(
            User::whereHas('roles', function($q) {
                $q->where('name', 'super_admin');
            })->get(),
            'New admin complaint',
            $admin->name . ' submitted "' . $request->subject . '".',
            [
                'type' => 'admin_complaint',
                'complaint_id' => $complaint->id,
                'link' => route('superadmin.complaints.index'),
            ]
        );

        return redirect()->route('admin.complaints.sent')->with('success', 'Complaint submitted to Super Admin successfully');
    }

    // Keep old method for backward compatibility
    public function superAdminComplaints()
    {
        $admin = auth()->user();

        $complaints = Complain::where('complaint_type', 'ADMIN_TO_SUPERADMIN')
            ->where('admin_id', $admin->id)
            ->orderByDesc('created_at')
            ->get();

        return view('blades.admin.superadmin-complaints', compact('complaints'));
    }

    // Create Complaint or Report
    public function createComplaintOrReport(Request $request)
    {
        $type = $request->get('type', 'complaint'); // complaint or report
        $targets = array_keys(config('complaints.targets.admin', []));
        return view('blades.admin.complaints.create', compact('targets'));
    }

    public function storeMessage(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;
        $type = $request->get('type', 'complaint'); // complaint or report

        $request->validate([
            'target_role' => 'required|string|in:' . implode(',', array_keys(config('complaints.targets.admin', []))),
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'location_link' => 'nullable|url',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('complaints', 'public');
            }
        }

        $complaintType = Complain::resolveType('admin', $request->target_role);
        
        $complaint = Complain::create([
            'type' => $type === 'report' ? Complain::TYPE_REPORT : Complain::TYPE_COMPLAINT,
            'complaint_type' => $complaintType,
            'admin_id' => $admin->id,
            'agency_id' => $agencyId,
            'sender_user_id' => $admin->id,
            'sender_role' => 'admin',
            'target_role' => $request->target_role,
            'subject' => $request->subject,
            'message' => $request->message,
            'location_link' => $request->location_link,
            'images' => !empty($images) ? $images : null,
            'status' => 'PENDING',
        ]);

        $notificationType = $type === 'report' ? 'report_created' : 'complaint_created';
        $itemType = $type === 'report' ? 'report' : 'complaint';
        
        NotificationService::sendToAgencyRole(
            $request->target_role,
            $agencyId,
            'New ' . $itemType . ' from ' . $admin->name,
            'Subject: ' . $request->subject,
            [
                'type' => $notificationType,
                'complaint_id' => $complaint->id,
                'link' => $this->getComplaintOrReportLink($request->target_role, $complaint->id),
            ]
        );

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint submitted successfully.');
    }

    public function showComplaint($id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $complaint = Complain::with(['sender', 'targetUser', 'admin', 'gestionnaire', 'artist.user', 'artist.agency', 'agentProfile.user'])
            ->where('type', Complain::TYPE_COMPLAINT)
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id)
                      ->orWhere('sender_user_id', $admin->id);
            })
            ->find($id);
        
        if (!$complaint) {
            return view('blades.shared.content-not-found', [
                'pageTitle' => 'Complaint Not Found',
                'title' => 'Complaint Not Found',
                'message' => 'Sorry, the complaint you are looking for does not exist or has been deleted.',
                'backUrl' => route('admin.complaints.index'),
            ]);
        }

        $gestionnaires = User::whereHas('roles', function($q) { 
                $q->where('name', 'gestionnaire'); 
            })
            ->where('agency_id', $agencyId)
            ->get();
        
        return view('blades.admin.complaints.show', compact('complaint', 'gestionnaires'));
    }

    public function deleteComplaint($id)
    {
        $admin = auth()->user();
        
        // Admin can delete any complaint they see in their table (complaints only, not reports)
        $complaint = Complain::where('type', Complain::TYPE_COMPLAINT)
            ->findOrFail($id);
        
        $complaint->hideForUser(Auth::id());
        
        return redirect()->route('admin.complaints.index')->with('success', 'Complaint deleted successfully');
    }

    public function showReport($id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $report = Complain::with(['sender', 'targetUser', 'admin', 'gestionnaire', 'artist.user', 'artist.agency', 'agentProfile.user'])
            ->where('type', Complain::TYPE_REPORT)
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id)
                      ->orWhere('sender_user_id', $admin->id);
            })
            ->find($id);
        
        if (!$report) {
            return view('blades.shared.content-not-found', [
                'pageTitle' => 'Report Not Found',
                'title' => 'Report Not Found',
                'message' => 'Sorry, the report you are looking for does not exist or has been deleted.',
                'backUrl' => route('admin.reports.index'),
            ]);
        }
        
        return view('blades.admin.reports.show', compact('report'));
    }

    public function respondToReport(Request $request, $id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $request->validate([
            'admin_response' => 'required|string',
            'admin_response_images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ], [
            'admin_response_images.*.max' => 'Each image must not be larger than 10MB.',
            'admin_response_images.*.image' => 'Each file must be an image.',
        ]);

        $report = Complain::where('type', Complain::TYPE_REPORT)
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            })
            ->findOrFail($id);
        
        $adminResponseImages = [];
        if ($request->hasFile('admin_response_images')) {
            $uploadedImages = $request->file('admin_response_images');
            if (count($uploadedImages) > 5) {
                return redirect()->back()->withErrors(['admin_response_images' => 'You can upload maximum 5 images.'])->withInput();
            }
            
            foreach ($uploadedImages as $image) {
                $adminResponseImages[] = $image->store('report_responses', 'public');
            }
        }

        $report->admin_response = $request->admin_response;
        $report->admin_response_images = !empty($adminResponseImages) ? $adminResponseImages : null;
        $report->admin_id = auth()->id();
        $report->status = 'RESOLVED';
        $report->save();

        if ($report->sender) {
            NotificationService::send(
                $report->sender,
                'Report answered',
                'Admin responded to your report "' . $report->subject . '".',
                [
                    'type' => 'report_answered',
                    'complaint_id' => $report->id,
                    'link' => $this->getComplaintOrReportLink($report->sender_role, $report->id),
                ]
            );
        }

        return redirect()->route('admin.reports.index')->with('success', 'Response sent successfully');
    }

    public function resolveReport($id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $report = Complain::where('type', Complain::TYPE_REPORT)
            ->where(function ($query) use ($agencyId, $admin) {
                $query->where('agency_id', $agencyId)
                      ->orWhere('target_user_id', $admin->id);
            })
            ->findOrFail($id);
        
        $report->status = 'RESOLVED';
        $report->admin_id = $admin->id;
        $report->save();

        if ($report->sender) {
            NotificationService::send(
                $report->sender,
                'Report resolved',
                'Your report "' . $report->subject . '" was marked as resolved.',
                [
                    'type' => 'report_resolved',
                    'complaint_id' => $report->id,
                    'link' => $this->getComplaintOrReportLink($report->sender_role, $report->id),
                ]
            );
        }

        return redirect()->route('admin.reports.index')->with('success', 'Report resolved successfully');
    }

    // Inbox for Complaints and Reports
    public function inbox(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;
        $type = $request->get('type', 'complaint');

        $query = Complain::with(['sender', 'artist.user', 'agentProfile.user', 'gestionnaire'])
            ->inbox('admin', $admin->id, $agencyId)
            ->notHiddenBy(Auth::id());

        if ($type === 'complaint') {
            $query->complaints();
        } elseif ($type === 'report') {
            $query->reports();
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.admin.complaints.inbox', compact('items', 'admin'));
    }

    // Sent Complaints and Reports
    public function sent(Request $request)
    {
        $admin = auth()->user();
        $type = $request->get('type', 'complaint');

        $query = Complain::with(['targetUser', 'gestionnaire', 'agency'])
            ->sent($admin->id)
            ->notHiddenBy(Auth::id());

        if ($type === 'complaint') {
            $query->complaints();
        } elseif ($type === 'report') {
            $query->reports();
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.admin.complaints.sent', compact('items', 'admin'));
    }

    private function getComplaintOrReportLink($targetRole, $complaintId)
    {
        $routes = [
            'gestionnaire' => route('gestionnaire.complaints.show', $complaintId),
            'agent' => route('agent.complaints.show', $complaintId),
            'super_admin' => route('superadmin.view-admin-complaint', $complaintId),
        ];

        return $routes[$targetRole] ?? route('admin.complaints.index');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('blades.admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path && \Storage::disk('public')->exists($user->profile_photo_path)) {
                \Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo_path = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }

    public function manageAgents()
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $agents = Agent::with('user')
            ->where('agency_id', $agencyId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('blades.admin.manage-agents', compact('agents'));
    }

    public function createAgent()
    {
        return view('blades.admin.create-agent');
    }

    public function storeAgent(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'badge_number' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'agency_id' => $agencyId,
            'role_id' => Role::where('name', 'agent')->first()->id,
        ]);
        $user->assignRole('agent');

        $agent = Agent::create([
            'user_id' => $user->id,
            'agency_id' => $agencyId,
            'badge_number' => $request->badge_number ?: 'AG-' . now()->format('ymd') . '-' . $user->id,
        ]);

        NotificationService::send(
            $admin->id,
            'New agent added',
            'You have successfully added agent ' . $user->name . '.',
            [
                'type' => 'agent_created',
                'agent_id' => $agent->id,
                'link' => route('admin.manage-agents'),
            ]
        );

        return redirect()->route('admin.manage-agents')->with('success', 'Agent created successfully.');
    }

    public function removeAgent($id)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $agent = Agent::where('agency_id', $agencyId)->findOrFail($id);
        $user = $agent->user;

        $agent->forceDelete();
        if ($user) {
            $user->removeRole('agent');
            $user->forceDelete();
        }

        return redirect()->route('admin.manage-agents')->with('success', 'Agent removed successfully');
    }

    public function manageMissions(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        $query = Mission::with(['agent.user', 'pv', 'gestionnaire'])
            ->where('agency_id', $agencyId);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $missions = $query->orderByDesc('scheduled_at')
            ->paginate(15);

        return view('blades.admin.manage-missions', compact('missions'));
    }

    public function createMission()
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        // Get agents for the agency
        $agents = Agent::with('user')
            ->where('agency_id', $agencyId)
            ->get();

        // Get gestionnaires for assignment
        $gestionnaires = User::whereHas('roles', function($q) {
                $q->where('name', 'gestionnaire');
            })
            ->where('agency_id', $agencyId)
            ->get();

        return view('blades.admin.create-mission', compact('agents', 'gestionnaires'));
    }

    public function storeMission(Request $request)
    {
        $admin = auth()->user();
        $agencyId = $admin->agency_id;

        // Custom validation: at least one recipient must be selected
        $request->validate([
            'gestionnaire_id' => 'nullable|exists:users,id',
            'agent_id' => 'nullable|exists:agents,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location_text' => 'nullable|string|max:255',
            'map_link' => 'nullable|url',
            'scheduled_at' => 'nullable|date',
        ]);

        // Check if at least one recipient is selected
        $assignToGestionnaire = $request->has('assign_to_gestionnaire') && $request->filled('gestionnaire_id');
        $assignToAgent = $request->has('assign_to_agent') && $request->filled('agent_id');

        if (!$assignToGestionnaire && !$assignToAgent) {
            return redirect()->back()
                ->withErrors(['recipient' => 'Please select at least one recipient (Gestionnaire or Agent)'])
                ->withInput();
        }

        $gestionnaire = null;
        $agent = null;

        // Verify and get gestionnaire if selected
        if ($assignToGestionnaire) {
            $gestionnaire = User::whereHas('roles', function ($q) {
                    $q->where('name', 'gestionnaire');
                })
                ->where('agency_id', $agencyId)
                ->findOrFail($request->gestionnaire_id);
        }

        // Verify and get agent if selected
        if ($assignToAgent) {
            $agent = Agent::where('agency_id', $agencyId)
                ->findOrFail($request->agent_id);
        }

        $mission = Mission::create([
            'agency_id' => $agencyId,
            'gestionnaire_id' => $gestionnaire?->id,
            'agent_id' => $agent?->id,
            'title' => $request->title,
            'description' => $request->description ?? null,
            'location_text' => $request->location_text ?? null,
            'map_link' => $request->map_link ?? null,
            'scheduled_at' => $request->scheduled_at ?? null,
            'status' => 'ASSIGNED',
        ]);

        // Send notification to gestionnaire if selected
        if ($gestionnaire) {
            NotificationService::send(
                $gestionnaire,
                'New mission assigned to you',
                'Admin assigned mission "' . $mission->title . '" for you to handle.',
                [
                    'type' => 'mission_assigned',
                    'mission_id' => $mission->id,
                    'link' => route('gestionnaire.missions.show', $mission->id),
                ]
            );
        }

        // Send notification to agent if selected
        if ($agent && $agent->user) {
            NotificationService::send(
                $agent->user,
                'New mission assigned',
                'Mission "' . $mission->title . '" has been assigned to you.',
                [
                    'type' => 'mission_assigned',
                    'mission_id' => $mission->id,
                    'link' => route('agent.missions.show', $mission->id),
                ]
            );
        }

        $recipients = [];
        if ($gestionnaire) $recipients[] = 'gestionnaire';
        if ($agent) $recipients[] = 'agent';
        $recipientsText = implode(' and ', $recipients);

        return redirect()->route('admin.manage-missions')->with('success', 'Mission created and assigned to ' . $recipientsText . ' successfully.');
    }
}


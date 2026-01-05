<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Agency;
use App\Models\AgencyWallet;
use App\Models\Artwork;
use App\Models\Complain;
use App\Models\Mission;
use App\Models\PV;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GestionnaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:gestionnaire');
    }

    public function dashboard()
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;
        $agency = $gestionnaire->agency;

        // Gestionnaire can only see artworks from artists in their agency
        $pendingArtworksCount = Artwork::where('status', 'PENDING')
            ->when($agencyId, function($q) use ($agencyId) {
                return $q->whereHas('artist', function($query) use ($agencyId) {
                    $query->where('agency_id', $agencyId);
                });
            })
            ->count();
        
        $approvedArtworksCount = Artwork::where('status', 'APPROVED')
            ->when($agencyId, function($q) use ($agencyId) {
                return $q->whereHas('artist', function($query) use ($agencyId) {
                    $query->where('agency_id', $agencyId);
                });
            })
            ->count();
        
        $rejectedArtworksCount = Artwork::where('status', 'REJECTED')
            ->when($agencyId, function($q) use ($agencyId) {
                return $q->whereHas('artist', function($query) use ($agencyId) {
                    $query->where('agency_id', $agencyId);
                });
            })
            ->count();

        $missionStats = [
            'assigned' => Mission::where('agency_id', $agencyId)->where('status', 'ASSIGNED')->count(),
            'in_progress' => Mission::where('agency_id', $agencyId)->where('status', 'IN_PROGRESS')->count(),
            'done' => Mission::where('agency_id', $agencyId)->where('status', 'DONE')->count(),
        ];

        // Count pending wallet recharge requests
        $pendingRechargeRequests = \App\Models\WalletRechargeRequest::whereHas('artist', function($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->where('status', 'PENDING')
            ->count();

        $pvStats = [
            'open' => PV::where('agency_id', $agencyId)->where('status', 'OPEN')->count(),
            'pending_payment' => PV::where('agency_id', $agencyId)->where('payment_status', 'PENDING')->count(),
            'awaiting_release' => PV::where('agency_id', $agencyId)
                ->where('payment_status', 'VALIDATED')
                ->whereNull('funds_released_at')
                ->count(),
        ];

        $wallet = AgencyWallet::firstOrCreate(
            ['agency_id' => $agencyId],
            ['balance' => 0]
        );

        $complaintStats = [
            'assigned' => Complain::where('target_role', 'gestionnaire')
                ->where(function ($query) use ($gestionnaire, $agencyId) {
                    $query->where('target_user_id', $gestionnaire->id)
                        ->orWhere(function ($sub) use ($agencyId) {
                            $sub->whereNull('target_user_id')
                                ->where('agency_id', $agencyId);
                        });
                })
                ->where('type', Complain::TYPE_COMPLAINT)
                ->where('status', '!=', 'RESOLVED')
                ->count(),
            'new' => Complain::where('target_role', 'gestionnaire')
                ->where(function ($query) use ($gestionnaire, $agencyId) {
                    $query->where('target_user_id', $gestionnaire->id)
                        ->orWhere(function ($sub) use ($agencyId) {
                            $sub->whereNull('target_user_id')
                                ->where('agency_id', $agencyId);
                        });
                })
                ->where('type', Complain::TYPE_COMPLAINT)
                ->where('status', 'PENDING')
                ->count(),
        ];

        return view('blades.gestionnaire.dashboard', compact(
            'gestionnaire',
            'agency',
            'pendingRechargeRequests',
            'pendingArtworksCount', 
            'approvedArtworksCount', 
            'rejectedArtworksCount',
            'missionStats',
            'pvStats',
            'wallet',
            'complaintStats'
        ));
    }

    public function profile()
    {
        $user = Auth::user();

        return view('blades.gestionnaire.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['phone'])) {
            $user->phone = $validated['phone'];
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->save();

        return redirect()->route('gestionnaire.profile')->with('success', 'Profile updated successfully.');
    }

    public function artworks(Request $request)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $query = Artwork::with(['artist.user', 'artist.agency', 'category'])
            ->when($agencyId, function($q) use ($agencyId) {
                return $q->whereHas('artist', function($query) use ($agencyId) {
                    $query->where('agency_id', $agencyId);
                });
            });

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $artworks = $query->orderBy('created_at', 'desc')->get();

        return view('blades.gestionnaire.artworks.index', compact('artworks'));
    }

    public function showArtwork($id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $artwork = Artwork::with(['artist.user', 'artist.agency', 'category'])
            ->whereHas('artist', function($query) use ($agencyId) {
                if ($agencyId) {
                    $query->where('agency_id', $agencyId);
                }
            })
            ->find($id);
        
        if (!$artwork) {
            return view('blades.shared.content-not-found', [
                'pageTitle' => 'Artwork Not Found',
                'title' => 'Artwork Not Found',
                'message' => 'Sorry, the artwork you are looking for does not exist or has been deleted.',
                'backUrl' => route('gestionnaire.artworks'),
            ]);
        }

        return view('blades.gestionnaire.artworks.show', compact('artwork'));
    }

    public function approveArtwork($id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $artwork = Artwork::whereHas('artist', function($query) use ($agencyId) {
                if ($agencyId) {
                    $query->where('agency_id', $agencyId);
                }
            })
            ->findOrFail($id);
        $artwork->loadMissing('artist.user');
        
        $artwork->status = 'APPROVED';
        $artwork->rejection_reason = null;
        // Set platform tax status to PENDING when approved (only if not already paid)
        if ($artwork->platform_tax_status !== 'PAID') {
            $artwork->platform_tax_status = 'PENDING';
            // Only set amount if not already set (preserve original amount if already paid)
            if (!$artwork->platform_tax_amount) {
                $artwork->platform_tax_amount = Artwork::calculatePlatformTax($artwork->category);
            }
        }
        $artwork->save();

        NotificationService::send(
            $artwork->artist?->user,
            'Artwork approved',
            'Your artwork "' . $artwork->title . '" is approved. Please pay the platform tax to activate it.',
            [
                'type' => 'artwork_approved',
                'artwork_id' => $artwork->id,
                'link' => route('artist.show-artwork', $artwork->id),
            ]
        );

        return redirect()->route('gestionnaire.artworks')->with('success', 'Artwork approved successfully. Artist must pay platform tax to activate it.');
    }

    public function rejectArtwork(Request $request, $id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $artwork = Artwork::whereHas('artist', function($query) use ($agencyId) {
                if ($agencyId) {
                    $query->where('agency_id', $agencyId);
                }
            })
            ->findOrFail($id);
        $artwork->loadMissing('artist.user');
        
        $artwork->status = 'REJECTED';
        $artwork->rejection_reason = $request->rejection_reason;
        $artwork->save();

        NotificationService::send(
            $artwork->artist?->user,
            'Artwork rejected',
            'Unfortunately "' . $artwork->title . '" was rejected. Reason: ' . $request->rejection_reason,
            [
                'type' => 'artwork_rejected',
                'artwork_id' => $artwork->id,
                'link' => route('artist.show-artwork', $artwork->id),
            ]
        );

        return redirect()->route('gestionnaire.artworks')->with('success', 'Artwork rejected');
    }

    public function agencies()
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;
        
        $agencies = Agency::when($agencyId, function($q) use ($agencyId) {
                return $q->where('id', $agencyId);
            })
            ->get();
        
        return view('blades.gestionnaire.agencies.index', compact('agencies'));
    }

    public function downloadArtwork($id)
    {
        $artwork = Artwork::findOrFail($id);
        
        if (!$artwork->file_path) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($artwork->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->response($artwork->file_path);
    }

    public function agents()
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $agents = Agent::with('user')
            ->where('agency_id', $agencyId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('blades.gestionnaire.agents.index', compact('agents'));
    }

    public function createAgent()
    {
        return view('blades.gestionnaire.agents.create');
    }

    public function storeAgent(Request $request)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

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
        ]);
        $user->assignRole('agent');

        $agentModel = Agent::create([
            'user_id' => $user->id,
            'agency_id' => $agencyId,
            'badge_number' => $request->badge_number ?: 'AG-' . now()->format('ymd') . '-' . $user->id,
        ]);

        NotificationService::sendToAgencyRole(
            'admin',
            $agencyId,
            'New agent added',
            $gestionnaire->name . ' added agent ' . $user->name . '.',
            [
                'type' => 'agent_created',
                'agent_id' => $agentModel->id,
                'link' => route('admin.dashboard'),
            ]
        );

        return redirect()->route('gestionnaire.agents.index')->with('success', 'Agent created successfully.');
    }

    public function pvs(Request $request)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $query = PV::with(['agent.user', 'artworkUsages'])
            ->where('agency_id', $agencyId);

        // Apply filters based on query parameter
        $filter = $request->get('filter');
        if ($filter === 'pending_payment') {
            $query->where('payment_status', 'PENDING');
        } elseif ($filter === 'awaiting_release') {
            $query->where('payment_status', 'VALIDATED')
                  ->whereNull('funds_released_at');
        } elseif ($filter === 'open') {
            $query->where('status', 'OPEN');
        }

        $pvs = $query->orderBy('created_at', 'desc')->get();

        return view('blades.gestionnaire.pvs.index', compact('pvs', 'filter'));
    }

    public function showPv($id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $pv = PV::with([
                'agent.user',
                'mission',
                'devices',
                'artworkUsages.artwork.artist.user',
                'finalizedBy',
            ])
            ->where('agency_id', $agencyId)
            ->findOrFail($id);

        return view('blades.gestionnaire.pvs.show', compact('pv'));
    }

    // Messages System - Unified

    // Reports and Complaints System
    public function reportsAndComplaints(Request $request)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;
        $type = $request->get('type', 'all'); // all, complaint, or report

        // Get all items (complaints and reports) sent to gestionnaire
        $itemsQuery = Complain::with([
                'artist.user',
                'artist.agency',
                'agentProfile.user',
                'sender',
                'admin',
                'targetUser',
            ])
            ->where('target_role', 'gestionnaire')
            ->notHiddenBy(Auth::id())
            ->where(function ($query) use ($gestionnaire, $agencyId) {
                $query->where('target_user_id', $gestionnaire->id)
                    ->orWhere(function ($sub) use ($agencyId) {
                        $sub->whereNull('target_user_id')
                            ->where('agency_id', $agencyId);
                    });
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

        // Keep separate queries for stats and other views
        $complaintsQuery = Complain::with([
                'artist.user',
                'artist.agency',
                'agentProfile.user',
                'sender',
                'admin',
                'targetUser',
            ])
            ->where('target_role', 'gestionnaire')
            ->where('type', Complain::TYPE_COMPLAINT)
            ->notHiddenBy(Auth::id())
            ->where(function ($query) use ($gestionnaire, $agencyId) {
                $query->where('target_user_id', $gestionnaire->id)
                    ->orWhere(function ($sub) use ($agencyId) {
                        $sub->whereNull('target_user_id')
                            ->where('agency_id', $agencyId);
                    });
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
                'admin',
                'targetUser',
            ])
            ->where('target_role', 'gestionnaire')
            ->where('type', Complain::TYPE_REPORT)
            ->where(function ($query) use ($gestionnaire, $agencyId) {
                $query->where('target_user_id', $gestionnaire->id)
                    ->orWhere(function ($sub) use ($agencyId) {
                        $sub->whereNull('target_user_id')
                            ->where('agency_id', $agencyId);
                    });
            });

        if ($request->filled('status')) {
            $reportsQuery->where('status', $request->status);
        }

        $reports = $reportsQuery->orderByDesc('created_at')->paginate(20)->withQueryString();

        $stats = [
            'complaints_total' => Complain::where('target_role', 'gestionnaire')
                ->where('type', Complain::TYPE_COMPLAINT)
                ->where(function($q) use ($gestionnaire, $agencyId) {
                    $q->where('target_user_id', $gestionnaire->id)
                      ->orWhere(function($sub) use ($agencyId) {
                          $sub->whereNull('target_user_id')->where('agency_id', $agencyId);
                      });
                })->count(),
            'complaints_pending' => Complain::where('target_role', 'gestionnaire')
                ->where('type', Complain::TYPE_COMPLAINT)
                ->where('status', 'PENDING')
                ->where(function($q) use ($gestionnaire, $agencyId) {
                    $q->where('target_user_id', $gestionnaire->id)
                      ->orWhere(function($sub) use ($agencyId) {
                          $sub->whereNull('target_user_id')->where('agency_id', $agencyId);
                      });
                })->count(),
            'reports_total' => Complain::where('target_role', 'gestionnaire')
                ->where('type', Complain::TYPE_REPORT)
                ->where(function($q) use ($gestionnaire, $agencyId) {
                    $q->where('target_user_id', $gestionnaire->id)
                      ->orWhere(function($sub) use ($agencyId) {
                          $sub->whereNull('target_user_id')->where('agency_id', $agencyId);
                      });
                })->count(),
            'reports_pending' => Complain::where('target_role', 'gestionnaire')
                ->where('type', Complain::TYPE_REPORT)
                ->where('status', 'PENDING')
                ->where(function($q) use ($gestionnaire, $agencyId) {
                    $q->where('target_user_id', $gestionnaire->id)
                      ->orWhere(function($sub) use ($agencyId) {
                          $sub->whereNull('target_user_id')->where('agency_id', $agencyId);
                      });
                })->count(),
        ];

        // Get agents for converting complaints to missions
        $agents = Agent::with('user')
            ->where('agency_id', $agencyId)
            ->get();
        
        return view('blades.gestionnaire.reports-and-complaints.index', compact('items', 'complaints', 'reports', 'gestionnaire', 'stats', 'agents'));
    }

    public function messagesInbox(Request $request)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;
        $type = $request->get('type', 'all');
        
        $query = Complain::with(['sender', 'admin', 'agentProfile.user'])
            ->where('target_role', 'gestionnaire')
            ->where(function ($query) use ($gestionnaire, $agencyId) {
                $query->where('target_user_id', $gestionnaire->id)
                    ->orWhere(function ($sub) use ($agencyId) {
                        $sub->whereNull('target_user_id')
                            ->where('agency_id', $agencyId);
                    });
            });
        
        if ($type === 'complaint') {
            $query->complaints();
        } elseif ($type === 'report') {
            $query->reports();
        }
        
        $messages = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.gestionnaire.messages.inbox', compact('messages', 'gestionnaire', 'type'));
    }

    public function messagesSent(Request $request)
    {
        $gestionnaire = Auth::user();
        $type = $request->get('type', 'all');
        
        $query = Complain::with(['targetUser', 'admin', 'agentProfile.user'])
            ->where('sender_user_id', $gestionnaire->id)
            ->where('sender_role', 'gestionnaire');
        
        if ($type === 'complaint') {
            $query->complaints();
        } elseif ($type === 'report') {
            $query->reports();
        }
        
        $messages = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.gestionnaire.messages.sent', compact('messages', 'gestionnaire', 'type'));
    }

    public function createMessage(Request $request)
    {
        return $this->createComplaint($request);
    }

    public function storeMessage(Request $request)
    {
        return $this->storeComplaint($request);
    }

    public function showMessage($id)
    {
        return $this->showComplaint($id);
    }

    public function takeMessage($id)
    {
        return $this->assignToSelf($id);
    }

    public function updateMessageStatus(Request $request, $id)
    {
        return $this->updateComplaintStatus($request, $id);
    }

    public function respondMessage(Request $request, $id)
    {
        return $this->respondToComplaint($request, $id);
    }

    // Keep old methods for backward compatibility
    public function complaints(Request $request)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;
        $type = $request->get('type', 'all');

        // Get messages from artists
        $artistMessages = Complain::with(['artist.user', 'artist.agency', 'agency', 'admin', 'sender', 'agentProfile.user', 'targetUser'])
            ->whereHas('artist', function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->where(function ($query) use ($gestionnaire) {
                $query->where('gestionnaire_id', $gestionnaire->id)
                      ->orWhereNull('gestionnaire_id');
            })
            ->notHiddenBy(Auth::id());

        if ($type === 'complaint') {
            $artistMessages->complaints();
        } elseif ($type === 'report') {
            $artistMessages->reports();
        }

        $artistMessages = $artistMessages->orderByDesc('created_at')->get();

        // Get messages sent by this gestionnaire
        $submitted = Complain::with(['agency', 'admin', 'sender', 'targetUser', 'agentProfile.user', 'artist.user', 'artist.agency'])
            ->where('sender_user_id', $gestionnaire->id)
            ->where('sender_role', 'gestionnaire')
            ->notHiddenBy(Auth::id());

        if ($type === 'complaint') {
            $submitted->complaints();
        } elseif ($type === 'report') {
            $submitted->reports();
        }

        $submitted = $submitted->orderByDesc('created_at')->get();

        // Get messages targeted to gestionnaires
        $inbox = Complain::with(['agency', 'sender', 'admin', 'agentProfile.user', 'artist.user', 'artist.agency'])
            ->where('target_role', 'gestionnaire')
            ->notHiddenBy(Auth::id())
            ->where(function ($query) use ($gestionnaire, $agencyId) {
                $query->where('target_user_id', $gestionnaire->id)
                    ->orWhere(function ($sub) use ($agencyId) {
                        $sub->whereNull('target_user_id')
                            ->where('agency_id', $agencyId);
                    });
            });

        if ($type === 'complaint') {
            $inbox->complaints();
        } elseif ($type === 'report') {
            $inbox->reports();
        }

        $inbox = $inbox->orderByDesc('created_at')->get();

        return view('blades.gestionnaire.complaints.index', compact('artistMessages', 'submitted', 'inbox', 'gestionnaire', 'type'));
    }

    public function showComplaint($id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        // Try to find complaint - could be from artist, sent by gestionnaire, or targeted to gestionnaire
        $complaint = Complain::with(['artist.user', 'artist.agency', 'agency', 'admin', 'sender', 'targetUser', 'agentProfile.user'])
            ->where(function ($query) use ($gestionnaire, $agencyId) {
                // Complaints from artists
                $query->whereHas('artist', function ($artistQuery) use ($agencyId) {
                        $artistQuery->where('agency_id', $agencyId);
                    })
                    ->where(function ($artistSub) use ($gestionnaire) {
                        $artistSub->where('gestionnaire_id', $gestionnaire->id)
                              ->orWhereNull('gestionnaire_id');
                    })
                    // OR complaints sent by this gestionnaire
                    ->orWhere('sender_user_id', $gestionnaire->id)
                    // OR complaints targeted to gestionnaires
                    ->orWhere(function ($targetSub) use ($gestionnaire, $agencyId) {
                        $targetSub->where('target_role', 'gestionnaire')
                            ->where(function ($targetInner) use ($gestionnaire, $agencyId) {
                                $targetInner->where('target_user_id', $gestionnaire->id)
                                    ->orWhere(function ($nullTarget) use ($agencyId) {
                                        $nullTarget->whereNull('target_user_id')
                                            ->where('agency_id', $agencyId);
                                    });
                            });
                    });
            })
            ->find($id);
        
        if (!$complaint) {
            return view('blades.shared.content-not-found', [
                'pageTitle' => 'Complaint Not Found',
                'title' => 'Complaint Not Found',
                'message' => 'Sorry, the complaint you are looking for does not exist or has been deleted.',
                'backUrl' => route('gestionnaire.reports-and-complaints.index'),
            ]);
        }

        return view('blades.gestionnaire.complaints.show', compact('complaint', 'gestionnaire'));
    }

    public function deleteComplaint($id)
    {
        $gestionnaire = Auth::user();
        
        // Gestionnaire can delete any complaint they see in their table (complaints only, not reports)
        $complaint = Complain::where('type', Complain::TYPE_COMPLAINT)
            ->findOrFail($id);
        
        $complaint->hideForUser(Auth::id());
        
        return redirect()->route('gestionnaire.complaints.index')->with('success', 'Complaint deleted successfully');
    }

    public function assignToSelf($id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $complaint = Complain::whereHas('artist', function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->whereNull('gestionnaire_id')
            ->findOrFail($id);

        $complaint->gestionnaire_id = $gestionnaire->id;
        $complaint->status = 'IN_PROGRESS';
        $complaint->save();

        NotificationService::sendToAgencyRole(
            'admin',
            $agencyId,
            $gestionnaire->name . ' is handling complaint #' . $complaint->id,
            'The complaint "' . $complaint->subject . '" is now in progress.',
            [
                'type' => 'complaint_taken',
                'complaint_id' => $complaint->id,
                'link' => route('admin.complaints.show', $complaint->id),
            ]
        );

        if ($complaint->artist && $complaint->artist->user) {
            NotificationService::send(
                $complaint->artist->user,
                'Complaint assigned',
                $gestionnaire->name . ' is now handling your complaint "' . $complaint->subject . '".',
                [
                    'type' => 'complaint_in_progress',
                    'complaint_id' => $complaint->id,
                    'link' => route('artist.complaints.index'),
                ]
            );
        }

        return redirect()->back()->with('success', 'Complaint assigned to you.');
    }

    public function updateComplaintStatus(Request $request, $id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $request->validate([
            'status' => 'required|in:PENDING,IN_PROGRESS,RESOLVED',
        ]);

        $complaint = Complain::whereHas('artist', function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->where('gestionnaire_id', $gestionnaire->id)
            ->findOrFail($id);

        $complaint->status = $request->status;
        $complaint->save();

        if ($complaint->artist && $complaint->artist->user) {
            NotificationService::send(
                $complaint->artist->user,
                'Complaint status updated',
                'Status changed to ' . str_replace('_', ' ', $request->status) . ' for "' . $complaint->subject . '".',
                [
                    'type' => 'complaint_status_updated',
                    'complaint_id' => $complaint->id,
                    'link' => route('artist.complaints.index'),
                ]
            );
        }

        NotificationService::sendToAgencyRole(
            'admin',
            $agencyId,
            'Complaint #' . $complaint->id . ' status updated',
            $gestionnaire->name . ' set status to ' . $request->status . '.',
            [
                'type' => 'complaint_status_updated',
                'complaint_id' => $complaint->id,
                'link' => route('admin.complaints.show', $complaint->id),
            ]
        );

        return redirect()->back()->with('success', 'Complaint status updated.');
    }

    public function finalizePV($id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        $pv = PV::where('agency_id', $agencyId)
            ->findOrFail($id);

        if (!$pv->canBeFinalized()) {
            return redirect()->back()->withErrors(['pv' => 'PV cannot be finalized. It must be closed, payment validated, and funds released.']);
        }

        $pv->finalized_at = now();
        $pv->finalized_by = $gestionnaire->id;
        $pv->save();

        if ($pv->agent && $pv->agent->user) {
            NotificationService::send(
                $pv->agent->user,
                'PV #' . $pv->id . ' finalized',
                'Gestionnaire confirmed and finalized your PV for ' . $pv->shop_name . '.',
                [
                    'type' => 'pv_finalized',
                    'pv_id' => $pv->id,
                    'link' => route('agent.pvs.show', $pv->id),
                ]
            );
        }

        return redirect()->back()->with('success', 'PV finalized successfully.');
    }

    public function createComplaint(Request $request)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;
        $type = $request->get('type', 'complaint'); // complaint or report
        
        // Get admins from same agency
        $admins = \App\Models\User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })
            ->where('agency_id', $agencyId)
            ->get();

        return view('blades.gestionnaire.reports-and-complaints.create', compact('admins', 'type', 'gestionnaire'));
    }

    public function storeComplaint(Request $request)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;
        $type = $request->get('type', 'complaint'); // complaint or report

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'location_link' => 'nullable|url|max:255',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png',
            'files.*' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
            'target_role' => 'required|in:admin,super_admin',
            'target_user_id' => 'nullable|exists:users,id',
        ], [
            'images.*.max' => 'Each image must not be larger than 10MB.',
            'images.*.image' => 'Each file must be an image.',
            'files.*.max' => 'Each file must not be larger than 10MB.',
            'files.*.mimes' => 'Files must be PDF, DOC, DOCX, XLS, or XLSX.',
        ]);

        // For reports, only admin is allowed (not super_admin)
        if ($type === 'report' && $data['target_role'] !== 'admin') {
            return redirect()->back()->withErrors(['target_role' => 'Reports can only be sent to admins.'])->withInput();
        }

        // For complaints to super_admin, only complaints are allowed
        if ($data['target_role'] === 'super_admin' && $type !== 'complaint') {
            return redirect()->back()->withErrors(['type' => 'Only complaints can be sent to super admin.'])->withInput();
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

        $files = [];
        if ($request->hasFile('files') && $type === 'report') {
            $uploadedFiles = $request->file('files');
            foreach ($uploadedFiles as $file) {
                $files[] = $file->store('reports', 'public');
            }
        }

        $targetRole = $data['target_role'];
        $targetUserId = $data['target_user_id'] ?? null;
        $complaintType = Complain::resolveType('gestionnaire', $targetRole);
        
        // Determine target user - Super Admin is automatic (only one exists)
        if ($targetRole === 'super_admin') {
            $superAdmin = \App\Models\User::whereHas('roles', function($q) {
                    $q->where('name', 'super_admin');
                })->first();
            $targetUserId = $superAdmin ? $superAdmin->id : null;
        } elseif ($targetRole === 'admin' && !$targetUserId) {
            $admin = \App\Models\User::whereHas('roles', function($q) {
                    $q->where('name', 'admin');
                })
                ->where('agency_id', $agencyId)
                ->first();
            $targetUserId = $admin ? $admin->id : null;
        }
        
        $complaint = Complain::create([
            'type' => $type === 'report' ? Complain::TYPE_REPORT : Complain::TYPE_COMPLAINT,
            'complaint_type' => $complaintType,
            'gestionnaire_id' => $gestionnaire->id,
            'admin_id' => $targetRole === 'admin' ? $targetUserId : null,
            'super_admin_id' => $targetRole === 'super_admin' ? $targetUserId : null,
            'agency_id' => $agencyId,
            'sender_user_id' => Auth::id(),
            'sender_role' => 'gestionnaire',
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
            : route('admin.reports-and-complaints.index');

        $notificationType = $type === 'report' ? 'report_created' : 'complaint_created';
        $itemType = $type === 'report' ? 'report' : 'complaint';

        if ($targetRole === 'super_admin') {
            $targetUser = \App\Models\User::find($targetUserId);
            if ($targetUser) {
                NotificationService::send(
                    $targetUser,
                    'New ' . $itemType . ' from ' . Auth::user()->name,
                    'Subject: ' . $data['subject'],
                    [
                        'type' => $notificationType,
                        'complaint_id' => $complaint->id,
                        'link' => $notificationLink,
                    ]
                );
            }
        } else {
            NotificationService::sendToAgencyRole(
                $targetRole,
                $agencyId,
                'New ' . $itemType . ' from ' . Auth::user()->name,
                'Subject: ' . $data['subject'],
                [
                    'type' => $notificationType,
                    'complaint_id' => $complaint->id,
                    'link' => $notificationLink,
                ]
            );
        }

        return redirect()->route('gestionnaire.reports-and-complaints.index')->with('success', ucfirst($type) . ' submitted successfully.');
    }

    public function respondToComplaint(Request $request, $id)
    {
        $gestionnaire = Auth::user();

        $complaint = Complain::where('target_role', 'gestionnaire')
            ->where(function ($query) use ($gestionnaire) {
                $query->where('target_user_id', Auth::id())
                    ->orWhere(function ($sub) use ($gestionnaire) {
                        $sub->whereNull('target_user_id')
                            ->where('agency_id', $gestionnaire->agency_id);
                    });
            })
            ->findOrFail($id);

        $data = $request->validate([
            'gestionnaire_response' => 'required|string',
            'gestionnaire_response_images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ], [
            'gestionnaire_response_images.*.max' => 'Each image must not be larger than 10MB.',
            'gestionnaire_response_images.*.image' => 'Each file must be an image.',
        ]);

        $responseImages = [];
        if ($request->hasFile('gestionnaire_response_images')) {
            $uploadedImages = $request->file('gestionnaire_response_images');
            if (count($uploadedImages) > 5) {
                return redirect()->back()->withErrors(['gestionnaire_response_images' => 'You can upload maximum 5 images.'])->withInput();
            }

            foreach ($uploadedImages as $image) {
                $responseImages[] = $image->store('complaint_responses', 'public');
            }
        }

        $complaint->gestionnaire_response = $data['gestionnaire_response'];
        $complaint->gestionnaire_response_images = !empty($responseImages) ? $responseImages : null;
        $complaint->gestionnaire_id = Auth::id();
        $complaint->status = 'RESOLVED';
        $complaint->responded_at = now();
        $complaint->save();

        // Notify sender
        if ($complaint->sender) {
            $link = match($complaint->sender_role) {
                'agent' => route('agent.complaints.show', $complaint->id),
                'admin' => route('admin.reports-and-complaints.index'),
                'gestionnaire' => route('gestionnaire.reports-and-complaints.index'),
                'artist' => route('artist.complaints.show', $complaint->id),
                default => route('dashboard')
            };

            NotificationService::send(
                $complaint->sender,
                'Complaint answered',
                'Gestionnaire responded to your complaint "' . $complaint->subject . '".',
                [
                    'type' => 'complaint_answered',
                    'complaint_id' => $complaint->id,
                    'link' => $link,
                ]
            );
        }

        return redirect()->route('gestionnaire.complaints.show', $complaint->id)->with('success', 'Response sent successfully.');
    }

    // Reports System Methods
    public function reports(Request $request)
    {
        return $this->reportsAndComplaints($request);
    }

    public function reportsInbox(Request $request)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;
        
        $query = Complain::with(['sender', 'admin', 'agentProfile.user'])
            ->where('target_role', 'gestionnaire')
            ->where('type', Complain::TYPE_REPORT)
            ->where(function ($query) use ($gestionnaire, $agencyId) {
                $query->where('target_user_id', $gestionnaire->id)
                    ->orWhere(function ($sub) use ($agencyId) {
                        $sub->whereNull('target_user_id')
                            ->where('agency_id', $agencyId);
                    });
            });
        
        $reports = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.gestionnaire.reports.inbox', compact('reports', 'gestionnaire'));
    }

    public function reportsSent(Request $request)
    {
        $gestionnaire = Auth::user();
        
        $query = Complain::with(['targetUser', 'admin', 'agentProfile.user'])
            ->where('sender_user_id', $gestionnaire->id)
            ->where('sender_role', 'gestionnaire')
            ->where('type', Complain::TYPE_REPORT);
        
        $reports = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.gestionnaire.reports.sent', compact('reports', 'gestionnaire'));
    }

    public function createReport(Request $request)
    {
        return $this->createComplaint($request);
    }

    public function storeReport(Request $request)
    {
        $request->merge(['type' => 'report']);
        return $this->storeComplaint($request);
    }

    public function showReport($id)
    {
        $gestionnaire = Auth::user();
        $agencyId = $gestionnaire->agency_id;

        // Try to find report - could be from artist, sent by gestionnaire, or targeted to gestionnaire
        $report = Complain::with(['artist.user', 'artist.agency', 'admin', 'sender', 'targetUser', 'agentProfile.user'])
            ->where('type', Complain::TYPE_REPORT)
            ->where(function ($query) use ($gestionnaire, $agencyId) {
                // Reports from artists
                $query->whereHas('artist', function ($artistQuery) use ($agencyId) {
                        $artistQuery->where('agency_id', $agencyId);
                    })
                    ->where(function ($artistSub) use ($gestionnaire) {
                        $artistSub->where('gestionnaire_id', $gestionnaire->id)
                              ->orWhereNull('gestionnaire_id');
                    })
                    // OR reports sent by this gestionnaire
                    ->orWhere('sender_user_id', $gestionnaire->id)
                    // OR reports targeted to gestionnaires
                    ->orWhere(function ($targetSub) use ($gestionnaire, $agencyId) {
                        $targetSub->where('target_role', 'gestionnaire')
                            ->where(function ($targetInner) use ($gestionnaire, $agencyId) {
                                $targetInner->where('target_user_id', $gestionnaire->id)
                                    ->orWhere(function ($nullTarget) use ($agencyId) {
                                        $nullTarget->whereNull('target_user_id')
                                            ->where('agency_id', $agencyId);
                                    });
                            });
                    });
            })
            ->find($id);
        
        if (!$report) {
            return view('blades.shared.content-not-found', [
                'pageTitle' => 'Report Not Found',
                'title' => 'Report Not Found',
                'message' => 'Sorry, the report you are looking for does not exist or has been deleted.',
                'backUrl' => route('gestionnaire.reports-and-complaints.index'),
            ]);
        }

        return view('blades.gestionnaire.reports.show', compact('report', 'gestionnaire'));
    }

    public function respondToReport(Request $request, $id)
    {
        $gestionnaire = Auth::user();

        $report = Complain::where('type', Complain::TYPE_REPORT)
            ->where('target_role', 'gestionnaire')
            ->where(function ($query) use ($gestionnaire) {
                $query->where('target_user_id', Auth::id())
                    ->orWhere(function ($sub) use ($gestionnaire) {
                        $sub->whereNull('target_user_id')
                            ->where('agency_id', $gestionnaire->agency_id);
                    });
            })
            ->findOrFail($id);

        $data = $request->validate([
            'gestionnaire_response' => 'required|string',
            'gestionnaire_response_images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ], [
            'gestionnaire_response_images.*.max' => 'Each image must not be larger than 10MB.',
            'gestionnaire_response_images.*.image' => 'Each file must be an image.',
        ]);

        $responseImages = [];
        if ($request->hasFile('gestionnaire_response_images')) {
            $uploadedImages = $request->file('gestionnaire_response_images');
            if (count($uploadedImages) > 5) {
                return redirect()->back()->withErrors(['gestionnaire_response_images' => 'You can upload maximum 5 images.'])->withInput();
            }

            foreach ($uploadedImages as $image) {
                $responseImages[] = $image->store('report_responses', 'public');
            }
        }

        $report->gestionnaire_response = $data['gestionnaire_response'];
        $report->gestionnaire_response_images = !empty($responseImages) ? $responseImages : null;
        $report->gestionnaire_id = Auth::id();
        $report->status = 'RESOLVED';
        $report->responded_at = now();
        $report->save();

        // Notify sender
        if ($report->sender) {
            NotificationService::send(
                $report->sender,
                'Report answered',
                'Gestionnaire responded to your report "' . $report->subject . '".',
                [
                    'type' => 'report_answered',
                    'complaint_id' => $report->id,
                    'link' => $report->sender_role === 'agent' ? route('agent.complaints.show', $report->id) : route('admin.reports.show', $report->id),
                ]
            );
        }

        return redirect()->route('gestionnaire.reports.show', $report->id)->with('success', 'Response sent successfully.');
    }
}


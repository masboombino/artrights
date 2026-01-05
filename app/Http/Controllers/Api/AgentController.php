<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Mission;
use App\Models\PV;
use App\Models\Complain;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Artwork;
use App\Models\Artist;
use App\Models\Agency;
use App\Models\PVArtwork;
use App\Models\Notification;
use App\Models\Law;
use App\Models\ShopType;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    protected function currentAgent()
    {
        return Auth::user()->agent;
    }

    // Dashboard Statistics
    public function getDashboard()
    {
        try {
            $agent = $this->currentAgent();

            if (!$agent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agent profile not found',
                ], 404);
            }

            $pvsQuery = PV::where('agent_id', $agent->id);
            $missionQuery = Mission::where('agent_id', $agent->id);

            $stats = [
                'missions_assigned' => (clone $missionQuery)->count(),
                'total_pvs' => (clone $pvsQuery)->count(),
                'open_pvs' => (clone $pvsQuery)->where('status', 'OPEN')->count(),
            ];

            $missionStats = [
                'assigned' => (clone $missionQuery)->where('status', 'ASSIGNED')->count(),
                'in_progress' => (clone $missionQuery)->where('status', 'IN_PROGRESS')->count(),
                'done' => (clone $missionQuery)->where('status', 'DONE')->count(),
            ];

            $pvStats = [
                'total' => (clone $pvsQuery)->count(),
                'open' => (clone $pvsQuery)->where('status', 'OPEN')->count(),
                'pending' => (clone $pvsQuery)->where('status', 'PENDING')->count(),
                'closed' => (clone $pvsQuery)->where('status', 'CLOSED')->count(),
                'validated' => (clone $pvsQuery)->where('payment_status', 'VALIDATED')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'mission_stats' => $missionStats,
                    'pv_stats' => $pvStats,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Profile Management
    public function getProfile()
    {
        try {
            $user = Auth::user();
            $agent = $user->agent;

            if (!$agent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agent profile not found',
                ], 404);
            }

            $agent->load('agency', 'user');

            // Use the User model's accessor for profile_photo_url
            // This ensures consistent URL building regardless of where the photo was uploaded
            $profilePhotoUrl = $user->profile_photo_url;

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'profile_photo_url' => $profilePhotoUrl,
                        'profile_photo_path' => $user->profile_photo_path,
                    ],
                    'agent' => [
                        'id' => $agent->id,
                        'badge_number' => $agent->badge_number,
                        'agency_name' => $agent->agency ? $agent->agency->agency_name : 'Not Assigned',
                        'wilaya' => $agent->agency ? $agent->agency->wilaya : 'Not Assigned',
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $agent = $user->agent;

        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Agent profile not found',
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'badge_number' => 'nullable|string|max:255',
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

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

        $agent->badge_number = $request->badge_number ?? $agent->badge_number;
        $agent->save();

        // Refresh user to get updated profile_photo_path
        $user->refresh();
        
        // Use the User model's accessor for profile_photo_url
        // This ensures consistent URL building regardless of where the photo was uploaded
        $profilePhotoUrl = $user->profile_photo_url;

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'profile_photo_url' => $profilePhotoUrl,
                    'profile_photo_path' => $user->profile_photo_path,
                ],
                'agent' => [
                    'id' => $agent->id,
                    'badge_number' => $agent->badge_number,
                    'agency_name' => $agent->agency ? $agent->agency->agency_name : 'Not Assigned',
                    'wilaya' => $agent->agency ? $agent->agency->wilaya : 'Not Assigned',
                ],
            ],
        ]);
    }

    // Missions Management
    public function getMissions(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return response()->json([
                'message' => 'Agent profile not found',
            ], 404);
        }

        $query = Mission::with('pv', 'agency')
            ->where('agent_id', $agent->id)
            ->orderByDesc('scheduled_at');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $missions = $query->get();

        return response()->json([
            'missions' => $missions->map(function ($mission) {
                return [
                    'id' => $mission->id,
                    'title' => $mission->title,
                    'description' => $mission->description,
                    'location_text' => $mission->location_text,
                    'map_link' => $mission->map_link,
                    'latitude' => $mission->latitude,
                    'longitude' => $mission->longitude,
                    'scheduled_at' => $mission->scheduled_at,
                    'status' => $mission->status,
                    'pv' => $mission->pv ? [
                        'id' => $mission->pv->id,
                        'shop_name' => $mission->pv->shop_name,
                    ] : null,
                    'agency' => $mission->agency ? [
                        'id' => $mission->agency->id,
                        'agency_name' => $mission->agency->agency_name,
                    ] : null,
                    'created_at' => $mission->created_at,
                ];
            }),
        ]);
    }

    public function getMission($id)
    {
        $agent = $this->currentAgent();
        $mission = Mission::with('pv', 'agency')
            ->where('agent_id', $agent->id)
            ->findOrFail($id);

        return response()->json([
            'mission' => [
                'id' => $mission->id,
                'title' => $mission->title,
                'description' => $mission->description,
                'location_text' => $mission->location_text,
                'map_link' => $mission->map_link,
                'latitude' => $mission->latitude,
                'longitude' => $mission->longitude,
                'scheduled_at' => $mission->scheduled_at,
                'status' => $mission->status,
                'pv' => $mission->pv ? [
                    'id' => $mission->pv->id,
                    'shop_name' => $mission->pv->shop_name,
                ] : null,
                'agency' => $mission->agency ? [
                    'id' => $mission->agency->id,
                    'agency_name' => $mission->agency->agency_name,
                ] : null,
                'created_at' => $mission->created_at,
                'updated_at' => $mission->updated_at,
            ],
        ]);
    }

    public function updateMissionStatus(Request $request, $id)
    {
        $agent = $this->currentAgent();
        $mission = Mission::where('agent_id', $agent->id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:ASSIGNED,IN_PROGRESS,DONE,CANCELLED',
        ]);

        $mission->status = $request->status;
        $mission->save();

        return response()->json([
            'message' => 'Mission status updated successfully',
            'mission' => [
                'id' => $mission->id,
                'status' => $mission->status,
            ],
        ]);
    }

    // PVs Management
    public function getPVs(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return response()->json([
                'message' => 'Agent profile not found',
            ], 404);
        }

        $query = PV::with(['agency', 'mission'])
            ->where('agent_id', $agent->id)
            ->orderByDesc('created_at');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pvs = $query->get();

        return response()->json([
            'pvs' => $pvs->map(function ($pv) {
                return [
                    'id' => $pv->id,
                    'shop_name' => $pv->shop_name,
                    'shop_type' => $pv->shop_type,
                    'date_of_inspection' => $pv->date_of_inspection,
                    'status' => $pv->status,
                    'payment_status' => $pv->payment_status,
                    'total_amount' => $pv->total_amount,
                    'notes' => $pv->notes,
                    'agency' => $pv->agency ? [
                        'id' => $pv->agency->id,
                        'agency_name' => $pv->agency->agency_name,
                        'wilaya' => $pv->agency->wilaya,
                    ] : null,
                    'mission' => $pv->mission ? [
                        'id' => $pv->mission->id,
                        'title' => $pv->mission->title,
                    ] : null,
                    'created_at' => $pv->created_at,
                ];
            }),
        ]);
    }

    public function getPV($id)
    {
        $agent = $this->currentAgent();
        $pv = PV::with(['agency', 'mission', 'devices.deviceType', 'artworkUsages.artwork.artist.user'])
            ->where('agent_id', $agent->id)
            ->findOrFail($id);

        return response()->json([
            'pv' => [
                'id' => $pv->id,
                'shop_name' => $pv->shop_name,
                'shop_type' => $pv->shop_type,
                'date_of_inspection' => $pv->date_of_inspection,
                'status' => $pv->status,
                'payment_status' => $pv->payment_status,
                'payment_method' => $pv->payment_method,
                'agent_payment_confirmed' => $pv->agent_payment_confirmed,
                'agent_confirmed_at' => $pv->agent_confirmed_at,
                'total_amount' => $pv->total_amount,
                'cash_received_amount' => $pv->cash_received_amount,
                'notes' => $pv->notes,
                'agency' => $pv->agency ? [
                    'id' => $pv->agency->id,
                    'agency_name' => $pv->agency->agency_name,
                    'wilaya' => $pv->agency->wilaya,
                ] : null,
                'mission' => $pv->mission ? [
                    'id' => $pv->mission->id,
                    'title' => $pv->mission->title,
                ] : null,
                'devices' => $pv->devices->map(function ($device) {
                    return [
                        'id' => $device->id,
                        'name' => $device->name,
                        'type' => $device->type,
                        'coefficient' => $device->coefficient,
                        'quantity' => $device->quantity,
                        'amount' => $device->amount,
                        'notes' => $device->notes,
                        'device_type' => $device->deviceType ? [
                            'id' => $device->deviceType->id,
                            'name' => $device->deviceType->name,
                            'type' => $device->deviceType->type,
                        ] : null,
                    ];
                }),
                'artwork_usages' => $pv->artworkUsages->map(function ($usage) {
                    return [
                        'id' => $usage->id,
                        'artwork' => $usage->artwork ? [
                            'id' => $usage->artwork->id,
                            'title' => $usage->artwork->title,
                            'artist' => $usage->artwork->artist ? [
                                'id' => $usage->artwork->artist->id,
                                'user' => [
                                    'name' => $usage->artwork->artist->user->name,
                                ],
                            ] : null,
                        ] : null,
                        'device' => $usage->device ? [
                            'id' => $usage->device->id,
                            'name' => $usage->device->name,
                        ] : null,
                        'hours_used' => $usage->hours_used,
                        'fine_amount' => $usage->fine_amount,
                        'notes' => $usage->notes,
                    ];
                }),
                'created_at' => $pv->created_at,
                'updated_at' => $pv->updated_at,
            ],
        ]);
    }

    public function createPV(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return response()->json([
                'message' => 'Agent profile not found',
            ], 404);
        }

        $request->validate([
            'mission_id' => 'nullable|exists:missions,id',
            'shop_name' => 'required|string|max:255',
            'shop_type' => 'required|string|max:255',
            'date_of_inspection' => 'required|date',
            'notes' => 'nullable|string',
            'report_files.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('report_files') && count($request->file('report_files')) > 25) {
            return response()->json([
                'message' => 'You can upload maximum 25 images.',
            ], 400);
        }

        $mission = null;
        if (!empty($request->mission_id)) {
            $mission = Mission::where('agent_id', $agent->id)->findOrFail($request->mission_id);
        }

        $mediaPaths = [];
        if ($request->hasFile('report_files')) {
            $files = $request->file('report_files');
            foreach ($files as $file) {
                $mediaPaths[] = $file->store('pv_reports', 'public');
            }
        }

        $pv = PV::create([
            'agent_id' => $agent->id,
            'agency_id' => $agent->agency_id,
            'mission_id' => $mission?->id,
            'shop_name' => $request->shop_name,
            'shop_type' => $request->shop_type,
            'date_of_inspection' => $request->date_of_inspection,
            'status' => 'OPEN',
            'payment_status' => 'PENDING',
            'notes' => $request->notes ?? null,
            'file_path' => !empty($mediaPaths) ? json_encode($mediaPaths) : null,
            'base_rate' => config('artrights.base_rate', 200),
            'total_amount' => 0,
        ]);

        if ($mission) {
            $mission->status = 'IN_PROGRESS';
            $mission->save();
        }

        NotificationService::sendToAgencyRole(
            'gestionnaire',
            $agent->agency_id,
            'New PV opened',
            Auth::user()->name . ' opened PV #' . $pv->id . ' for ' . $pv->shop_name . '.',
            [
                'type' => 'pv_opened',
                'pv_id' => $pv->id,
                'link' => route('gestionnaire.pvs.show', $pv->id),
            ]
        );

        return response()->json([
            'message' => 'PV created successfully',
            'pv' => [
                'id' => $pv->id,
                'shop_name' => $pv->shop_name,
                'status' => $pv->status,
            ],
        ], 201);
    }

    // Add Device to PV
    public function addDeviceToPV(Request $request, $pvId)
    {
        $agent = $this->currentAgent();
        $pv = PV::where('agent_id', $agent->id)->findOrFail($pvId);

        if ($pv->status !== 'OPEN') {
            return response()->json([
                'message' => 'Cannot add devices to a PV that is not open.',
            ], 400);
        }

        $request->validate([
            'device_type_id' => 'nullable|exists:device_types,id',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'coefficient' => 'required|numeric|min:0.1',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $deviceType = null;
        if (!empty($request->device_type_id)) {
            $deviceType = \App\Models\DeviceType::find($request->device_type_id);
        }

        $device = \App\Models\Device::create([
            'pv_id' => $pv->id,
            'device_type_id' => $deviceType?->id,
            'name' => $request->name,
            'type' => $request->type ?? $deviceType?->type ?? $request->name,
            'coefficient' => $request->coefficient,
            'quantity' => $request->quantity,
            'notes' => $request->notes ?? null,
            'amount' => 0,
        ]);

        return response()->json([
            'message' => 'Device added successfully',
            'device' => [
                'id' => $device->id,
                'name' => $device->name,
                'type' => $device->type,
                'coefficient' => $device->coefficient,
                'quantity' => $device->quantity,
            ],
        ], 201);
    }

    // Add Artwork to PV
    public function addArtworkToPV(Request $request, $pvId)
    {
        $agent = $this->currentAgent();
        $pv = PV::where('agent_id', $agent->id)->findOrFail($pvId);

        if ($pv->status !== 'OPEN') {
            return response()->json([
                'message' => 'Cannot add artworks to a PV that is not open.',
            ], 400);
        }

        $request->validate([
            'artwork_id' => 'required|exists:artworks,id',
            'device_id' => 'nullable|exists:devices,id',
            'calculation_method' => 'required|in:hours,count',
            'hours_used' => 'required_if:calculation_method,hours|numeric|min:0.5',
            'usage_count' => 'required_if:calculation_method,count|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $artwork = Artwork::with(['category', 'artist.user'])->findOrFail($request->artwork_id);

        if ($artwork->status !== 'APPROVED') {
            return response()->json([
                'message' => 'Only approved artworks can be used in PV calculations.',
            ], 400);
        }

        if ($artwork->platform_tax_status !== 'PAID') {
            return response()->json([
                'message' => 'This artwork cannot be used in PVs until the platform tax is paid by the artist.',
            ], 400);
        }

        $device = null;
        if (!empty($request->device_id)) {
            $device = \App\Models\Device::where('pv_id', $pv->id)->findOrFail($request->device_id);
        }

        $timeValue = $request->calculation_method === 'hours'
            ? $request->hours_used
            : $request->usage_count;

        $fine = $this->calculateFine(
            $artwork->category?->coefficient ?? 1,
            $device?->coefficient ?? 1,
            $timeValue,
            1, // plays_count is no longer used
            $pv
        );

        $pvArtwork = \App\Models\PVArtwork::create([
            'pv_id' => $pv->id,
            'artwork_id' => $artwork->id,
            'device_id' => $device?->id,
            'hours_used' => $request->calculation_method === 'hours' ? $request->hours_used : ($request->usage_count ?? 1),
            'plays_count' => 1,
            'base_rate' => $pv->base_rate,
            'fine_amount' => $fine,
            'notes' => $request->notes ?? null,
        ]);

        $pv->recalculateTotals();
        $this->updateDeviceAmount($device);

        if ($artwork->artist && $artwork->artist->user) {
            NotificationService::send(
                $artwork->artist->user,
                'Artwork used in PV #' . $pv->id,
                Auth::user()->name . ' recorded "' . $artwork->title . '" in a new PV.',
                [
                    'type' => 'pv_artwork_usage',
                    'artwork_id' => $artwork->id,
                    'pv_id' => $pv->id,
                    'link' => route('artist.show-artwork', $artwork->id),
                ]
            );
        }

        return response()->json([
            'message' => 'Artwork added to PV successfully',
            'artwork_usage' => [
                'id' => $pvArtwork->id,
                'artwork' => [
                    'id' => $artwork->id,
                    'title' => $artwork->title,
                    'artist' => $artwork->artist ? [
                        'name' => $artwork->artist->user->name,
                    ] : null,
                ],
                'device' => $device ? [
                    'id' => $device->id,
                    'name' => $device->name,
                ] : null,
                'hours_used' => $pvArtwork->hours_used,
                'fine_amount' => $pvArtwork->fine_amount,
            ],
        ], 201);
    }

    // Delete Device from PV
    public function deleteDeviceFromPV(Request $request, $pvId, $deviceId)
    {
        $agent = $this->currentAgent();
        $pv = PV::where('agent_id', $agent->id)->findOrFail($pvId);

        if ($pv->status !== 'OPEN') {
            return response()->json([
                'message' => 'Cannot delete devices from a PV that is not open.',
            ], 400);
        }

        $device = Device::where('pv_id', $pv->id)->findOrFail($deviceId);

        // Check if device has any artwork usages
        if ($device->usages()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete device that has artwork usages. Please delete all artwork usages from this device first.',
            ], 400);
        }

        $device->delete();

        // Recalculate PV totals after device deletion
        $pv->recalculateTotals();

        return response()->json([
            'message' => 'Device deleted successfully',
        ], 200);
    }

    // Delete Artwork Usage from PV
    public function deleteArtworkFromPV(Request $request, $pvId, $artworkUsageId)
    {
        $agent = $this->currentAgent();
        $pv = PV::where('agent_id', $agent->id)->findOrFail($pvId);

        if ($pv->status !== 'OPEN') {
            return response()->json([
                'message' => 'Cannot delete artwork usages from a PV that is not open.',
            ], 400);
        }

        $pvArtwork = PVArtwork::where('pv_id', $pv->id)->findOrFail($artworkUsageId);

        $device = $pvArtwork->device;
        $pvArtwork->delete();

        // Recalculate PV totals and update device amount
        $pv->recalculateTotals();
        $this->updateDeviceAmount($device);

        return response()->json([
            'message' => 'Artwork usage deleted successfully',
        ], 200);
    }

    // Close PV
    public function closePV(Request $request, $pvId)
    {
        $agent = $this->currentAgent();
        $pv = PV::where('agent_id', $agent->id)->with(['devices', 'artworkUsages'])->findOrFail($pvId);

        if ($pv->isFinalized()) {
            return response()->json([
                'success' => false,
                'message' => 'This PV has already been finalized by the agency.',
            ], 400);
        }

        if ($pv->status !== 'OPEN') {
            return response()->json([
                'success' => false,
                'message' => 'PV is not in OPEN status and cannot be closed.',
            ], 400);
        }

        // Validate that PV has required data before closing
        $errors = [];
        
        if ($pv->devices->isEmpty()) {
            $errors[] = 'PV must have at least one device before closing.';
        }
        
        if ($pv->artworkUsages->isEmpty()) {
            $errors[] = 'PV must have at least one artwork usage before closing.';
        }
        
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $errors),
                'errors' => $errors,
            ], 400);
        }

        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $pv->update([
            'status' => 'CLOSED',
            'notes' => $request->notes ?? $pv->notes,
            'closed_at' => now(),
        ]);

        if ($pv->mission) {
            $pv->mission->status = 'DONE';
            $pv->mission->save();
        }

        $agentAgencyId = $agent?->agency_id ?? $pv->agency_id;

        NotificationService::sendToAgencyRole(
            'gestionnaire',
            $agentAgencyId,
            'PV closed',
            Auth::user()->name . ' closed PV #' . $pv->id . '.',
            [
                'type' => 'pv_closed',
                'pv_id' => $pv->id,
                'link' => route('gestionnaire.pvs.show', $pv->id),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'PV closed successfully. Waiting for agency finalization.',
            'pv' => [
                'id' => $pv->id,
                'status' => $pv->status,
                'closed_at' => $pv->closed_at,
            ],
        ], 200);
    }

    // Update Payment Information
    public function updatePayment(Request $request, $pvId)
    {
        $agent = $this->currentAgent();
        $pv = PV::where('agent_id', $agent->id)->findOrFail($pvId);

        $data = $request->validate([
            'payment_method' => 'required|in:CASH,CHEQUE',
            'cash_received_amount' => 'nullable|numeric|min:0',
            'agent_payment_confirmed' => 'nullable|boolean',
        ]);

        $pv->payment_method = $data['payment_method'];

        // Agent payment confirmation
        if (isset($data['agent_payment_confirmed'])) {
            $pv->agent_payment_confirmed = $data['agent_payment_confirmed'];

            if ($data['agent_payment_confirmed']) {
                $pv->agent_confirmed_at = now();

                // Notify gestionnaire
                $gestionnaires = \App\Models\User::whereHas('roles', function($q) {
                        $q->where('name', 'gestionnaire');
                    })
                    ->where('agency_id', $pv->agency_id)
                    ->get();

                if ($gestionnaires->count() > 0) {
                    NotificationService::send(
                        $gestionnaires,
                        'Agent confirmed PV payment',
                        'Agent confirmed payment for PV #' . $pv->id . ' (' . $pv->shop_name . '). Please validate and add to agency wallet.',
                        [
                            'type' => 'pv_agent_confirmed',
                            'pv_id' => $pv->id,
                            'link' => route('gestionnaire.pvs.show', $pv->id),
                        ]
                    );
                }
            } else {
                $pv->agent_confirmed_at = null;
            }
        }

        if (isset($data['cash_received_amount'])) {
            $pv->cash_received_amount = $data['cash_received_amount'];
        }

        $pv->save();

        return response()->json([
            'message' => 'Payment information updated successfully.',
            'pv' => [
                'id' => $pv->id,
                'payment_method' => $pv->payment_method,
                'cash_received_amount' => $pv->cash_received_amount,
                'agent_payment_confirmed' => $pv->agent_payment_confirmed,
                'agent_confirmed_at' => $pv->agent_confirmed_at,
            ],
        ], 200);
    }

    // Upload Payment Proof
    public function uploadPaymentProof(Request $request, $pvId)
    {
        $agent = $this->currentAgent();
        $pv = PV::where('agent_id', $agent->id)->findOrFail($pvId);

        $data = $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);

        if ($pv->payment_proof_path) {
            Storage::disk('public')->delete($pv->payment_proof_path);
        }

        $pv->payment_proof_path = $request->file('payment_proof')->store('payment_proofs', 'public');
        $pv->save();

        return response()->json([
            'message' => 'Payment proof uploaded successfully.',
            'pv' => [
                'id' => $pv->id,
                'payment_proof_path' => $pv->payment_proof_path,
            ],
        ], 200);
    }

    public function uploadPhotos(Request $request, $pvId)
    {
        try {
            $agent = $this->currentAgent();
            $pv = PV::where('agent_id', $agent->id)->findOrFail($pvId);

            if ($pv->status !== 'OPEN') {
                return response()->json([
                    'success' => false,
                    'message' => 'Photos can only be uploaded to OPEN PVs.',
                ], 400);
            }

            $data = $request->validate([
                'photos.*' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:10240', // 10MB per image
            ]);

            $existingFiles = $pv->evidenceFiles();
            $currentCount = count($existingFiles);

            if ($request->hasFile('photos')) {
                $files = $request->file('photos');
                $totalAfterUpload = $currentCount + count($files);

                if ($totalAfterUpload > 100) {
                    return response()->json([
                        'success' => false,
                        'message' => "You can upload up to 100 photos. Currently you have $currentCount photos.",
                    ], 400);
                }

                $newPaths = [];
                foreach ($files as $file) {
                    $newPaths[] = $file->store('pv_evidence', 'public');
                }

                $allPaths = array_merge($existingFiles, $newPaths);
                $pv->file_path = json_encode($allPaths);
                $pv->save();

                // Reload PV with relationships
                $pv->load(['agency', 'mission', 'agent.user']);

                return response()->json([
                    'success' => true,
                    'message' => count($newPaths) . ' photo(s) uploaded successfully.',
                    'pv' => [
                        'id' => $pv->id,
                        'file_path' => $pv->file_path,
                        'evidence_files_count' => count($allPaths),
                    ],
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'No photos were uploaded.',
            ], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photos: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Get Artists by Agency
    public function getArtistsByAgency(Request $request, $pvId)
    {
        $agent = $this->currentAgent();
        $pv = PV::where('agent_id', $agent->id)->findOrFail($pvId);

        $request->validate([
            'agency_id' => 'required|exists:agencies,id',
        ]);

        $artists = Artist::where('agency_id', $request->agency_id)
            ->where('status', 'APPROVED')
            ->with('user')
            ->orderBy('stage_name')
            ->get();

        return response()->json([
            'artists' => $artists->map(function($artist) {
                return [
                    'id' => $artist->id,
                    'name' => $artist->user->name ?? $artist->stage_name ?? 'Unknown',
                    'stage_name' => $artist->stage_name,
                ];
            }),
        ]);
    }

    // Get Artworks by Artist
    public function getArtworksByArtist(Request $request, $pvId)
    {
        $agent = $this->currentAgent();
        $pv = PV::where('agent_id', $agent->id)->findOrFail($pvId);

        $request->validate([
            'artist_id' => 'required|exists:artists,id',
        ]);

        $artworks = Artwork::where('artist_id', $request->artist_id)
            ->where('status', 'APPROVED')
            ->where('platform_tax_status', 'PAID')
            ->with('category')
            ->orderBy('title')
            ->get();

        return response()->json([
            'artworks' => $artworks->map(function($artwork) {
                return [
                    'id' => $artwork->id,
                    'title' => $artwork->title,
                    'category' => $artwork->category->name ?? 'N/A',
                    'category_coefficient' => $artwork->category->coefficient ?? 1,
                ];
            }),
        ]);
    }

    // Shop Types for PV creation
    public function getShopTypes()
    {
        $shopTypes = ShopType::active()
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        $formattedShopTypes = [];
        foreach ($shopTypes as $category => $types) {
            $formattedShopTypes[] = [
                'category' => $category,
                'shop_types' => $types->map(function ($shopType) {
                    return [
                        'id' => $shopType->id,
                        'name' => $shopType->name,
                        'description' => $shopType->description,
                    ];
                })->toArray(),
            ];
        }

        return response()->json([
            'shop_types' => $formattedShopTypes,
        ]);
    }

    // Device Types for PV creation
    public function getDeviceTypes()
    {
        $deviceTypes = DeviceType::orderBy('type')->orderBy('name')->get();

        return response()->json([
            'device_types' => $deviceTypes->map(function ($deviceType) {
                return [
                    'id' => $deviceType->id,
                    'name' => $deviceType->name,
                    'type' => $deviceType->type,
                    'coefficient' => $deviceType->coefficient,
                ];
            }),
        ]);
    }

    // Agencies for artwork selection
    public function getAgencies()
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return response()->json([
                'message' => 'Agent profile not found',
            ], 404);
        }

        $agenciesQuery = Agency::whereHas('artists', function ($query) {
            $query->where('status', 'APPROVED');
        });

        if ($agent->agency_id) {
            $agenciesQuery->orWhere('id', $agent->agency_id);
        }

        $agencies = $agenciesQuery
            ->orderBy('wilaya')
            ->orderBy('agency_name')
            ->get()
            ->unique('id')
            ->values();

        return response()->json([
            'agencies' => $agencies->map(function ($agency) {
                return [
                    'id' => $agency->id,
                    'agency_name' => $agency->agency_name,
                    'wilaya' => $agency->wilaya,
                ];
            }),
        ]);
    }

    // Complaints Management (Agents can only send complaints/reports)
    public function getComplaints(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return response()->json([
                'message' => 'Agent profile not found',
            ], 404);
        }

        $query = Complain::with(['admin', 'gestionnaire', 'targetUser', 'sender'])
            ->where('agent_id', $agent->id)
            ->whereIn('type', [Complain::TYPE_COMPLAINT, Complain::TYPE_REPORT])
            ->orderByDesc('created_at');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $complaints = $query->get();

        return response()->json([
            'complaints' => $complaints->map(function ($complaint) {
                $images = [];
                if ($complaint->images && is_array($complaint->images)) {
                    foreach ($complaint->images as $image) {
                        if ($image) {
                            $cleanPath = ltrim($image, '/');
                            $images[] = '/api/media/' . $cleanPath;
                        }
                    }
                }

                $adminResponseImages = [];
                if ($complaint->admin_response_images && is_array($complaint->admin_response_images)) {
                    foreach ($complaint->admin_response_images as $image) {
                        if ($image) {
                            $cleanPath = ltrim($image, '/');
                            $adminResponseImages[] = '/api/media/' . $cleanPath;
                        }
                    }
                }

                $gestionnaireResponseImages = [];
                if ($complaint->gestionnaire_response_images && is_array($complaint->gestionnaire_response_images)) {
                    foreach ($complaint->gestionnaire_response_images as $image) {
                        if ($image) {
                            $cleanPath = ltrim($image, '/');
                            $gestionnaireResponseImages[] = '/api/media/' . $cleanPath;
                        }
                    }
                }

                return [
                    'id' => $complaint->id,
                    'type' => $complaint->type,
                    'subject' => $complaint->subject,
                    'message' => $complaint->message,
                    'status' => $complaint->status,
                    'target_role' => $complaint->target_role,
                    'images' => $images,
                    'location_link' => $complaint->location_link,
                    'admin_response' => $complaint->admin_response,
                    'gestionnaire_response' => $complaint->gestionnaire_response,
                    'admin_response_images' => $adminResponseImages,
                    'gestionnaire_response_images' => $gestionnaireResponseImages,
                    'responded_at' => $complaint->responded_at,
                    'created_at' => $complaint->created_at,
                    'updated_at' => $complaint->updated_at,
                ];
            }),
        ]);
    }

    public function getComplaint($id)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return response()->json([
                'message' => 'Agent profile not found',
            ], 404);
        }

        $complaint = Complain::with(['admin', 'gestionnaire', 'targetUser', 'sender'])
            ->where('agent_id', $agent->id)
            ->where('id', $id)
            ->first();

        if (!$complaint) {
            return response()->json([
                'message' => 'Complaint not found',
            ], 404);
        }

        $images = [];
        if ($complaint->images && is_array($complaint->images)) {
            foreach ($complaint->images as $image) {
                if ($image) {
                    $cleanPath = ltrim($image, '/');
                    $images[] = '/api/media/' . $cleanPath;
                }
            }
        }

        $adminResponseImages = [];
        if ($complaint->admin_response_images && is_array($complaint->admin_response_images)) {
            foreach ($complaint->admin_response_images as $image) {
                if ($image) {
                    $cleanPath = ltrim($image, '/');
                    $adminResponseImages[] = '/api/media/' . $cleanPath;
                }
            }
        }

        $gestionnaireResponseImages = [];
        if ($complaint->gestionnaire_response_images && is_array($complaint->gestionnaire_response_images)) {
            foreach ($complaint->gestionnaire_response_images as $image) {
                if ($image) {
                    $cleanPath = ltrim($image, '/');
                    $gestionnaireResponseImages[] = '/api/media/' . $cleanPath;
                }
            }
        }

        return response()->json([
            'complaint' => [
                'id' => $complaint->id,
                'type' => $complaint->type,
                'subject' => $complaint->subject,
                'message' => $complaint->message,
                'status' => $complaint->status,
                'target_role' => $complaint->target_role,
                'images' => $images,
                'location_link' => $complaint->location_link,
                'admin_response' => $complaint->admin_response,
                'gestionnaire_response' => $complaint->gestionnaire_response,
                'admin_response_images' => $adminResponseImages,
                'gestionnaire_response_images' => $gestionnaireResponseImages,
                'responded_at' => $complaint->responded_at,
                'created_at' => $complaint->created_at,
                'updated_at' => $complaint->updated_at,
            ],
        ]);
    }

    public function createComplaint(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent || !$agent->agency_id) {
            return response()->json([
                'message' => 'You must be associated with an agency to submit complaints.',
            ], 400);
        }

        $data = $request->validate([
            'type' => 'required|in:complaint,report',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'location_link' => 'nullable|url|max:255',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png',
            'target_role' => 'required|in:admin,gestionnaire',
            'target_user_id' => 'nullable|exists:users,id',
        ], [
            'images.*.max' => 'Each image must not be larger than 10MB.',
            'images.*.image' => 'Each file must be an image.',
            'target_user_id.exists' => 'Selected user does not exist.',
        ]);

        $type = $data['type'];

        if ($type === 'report' && $data['target_role'] !== 'gestionnaire') {
            return response()->json([
                'message' => 'Reports can only be sent to gestionnaires.',
            ], 400);
        }

        if ($type === 'complaint' && $data['target_role'] !== 'admin') {
            return response()->json([
                'message' => 'Complaints can only be sent to admins.',
            ], 400);
        }

        $images = [];
        if ($request->hasFile('images')) {
            $uploadedImages = $request->file('images');
            if (count($uploadedImages) > 5) {
                return response()->json([
                    'message' => 'You can upload maximum 5 images.',
                ], 400);
            }

            foreach ($uploadedImages as $image) {
                $images[] = $image->store('complaints', 'public');
            }
        }

        $targetRole = $data['target_role'];
        $complaintType = Complain::resolveType('agent', $targetRole);

        $targetUserId = $data['target_user_id'] ?? null;
        
        // If target_user_id is provided, validate it belongs to the same agency and has the correct role
        if ($targetUserId) {
            $targetUser = User::where('id', $targetUserId)
                ->where('agency_id', $agent->agency_id)
                ->whereHas('roles', function ($query) use ($targetRole) {
                    $query->where('name', $targetRole);
                })
                ->first();

            if (!$targetUser) {
                return response()->json([
                    'message' => 'Selected user is not valid or does not belong to your agency.',
                ], 400);
            }
        } else {
            // Fallback to old behavior if no user is selected
            if ($targetRole === 'admin') {
                $targetUserId = $agent->agency->admin_id;
            } elseif ($targetRole === 'gestionnaire') {
                $gestionnaire = $agent->agency->gestionnaires->first();
                $targetUserId = $gestionnaire ? $gestionnaire->id : null;
            }
        }

        $complaint = Complain::create([
            'type' => $type === 'report' ? Complain::TYPE_REPORT : Complain::TYPE_COMPLAINT,
            'complaint_type' => $complaintType,
            'agent_id' => $agent->id,
            'agency_id' => $agent->agency_id,
            'sender_user_id' => Auth::id(),
            'sender_role' => 'agent',
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
            $agent->agency_id,
            'New ' . ($type === 'report' ? 'report' : 'complaint') . ' from ' . Auth::user()->name,
            'Subject: ' . $data['subject'],
            [
                'type' => $type === 'report' ? 'report_created' : 'complaint_created',
                'complaint_id' => $complaint->id,
                'link' => $notificationLink,
            ]
        );

        return response()->json([
            'message' => ucfirst($type) . ' submitted successfully',
            'complaint' => [
                'id' => $complaint->id,
                'subject' => $complaint->subject,
                'status' => $complaint->status,
            ],
        ], 201);
    }

    // Notifications Management
    public function getNotifications(Request $request)
    {
        $user = Auth::user();

        $query = Notification::where('user_id', $user->id)
            ->with('sender')
            ->orderByDesc('created_at');

        if ($request->has('unread_only') && $request->unread_only) {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(15);

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => $notification->is_read,
                    'data' => $notification->data,
                    'sender' => $notification->sender ? [
                        'id' => $notification->sender->id,
                        'name' => $notification->sender->name,
                    ] : null,
                    'created_at' => $notification->created_at,
                ];
            }),
            'unread_count' => Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count(),
        ]);
    }

    public function markNotificationRead($id)
    {
        $user = Auth::user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->update([
            'is_read' => true,
        ]);

        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }

    public function markAllNotificationsRead()
    {
        $user = Auth::user();

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    public function deleteNotification($id)
    {
        $user = Auth::user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully',
        ]);
    }

    public function getUnreadCount()
    {
        $user = Auth::user();

        $count = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'unread_count' => $count,
        ]);
    }

    protected function updateDeviceAmount(?Device $device): void
    {
        if (!$device) {
            return;
        }

        $device->amount = $device->usages()->sum('fine_amount');
        $device->save();
    }

    protected function calculateFine(float $categoryCoefficient, float $deviceCoefficient, float $hours, int $plays, PV $pv): float
    {
        $baseRate = $pv->base_rate ?? config('artrights.base_rate', 200);

        return round($categoryCoefficient * $deviceCoefficient * max($hours, 0.5) * $baseRate, 2);
    }

    // Law Content
    public function getLaw()
    {
        try {
            $baseRate = config('artrights.base_rate', 200);

            $englishLaw = Law::where('language', 'english')->first();
            $arabicLaw = Law::where('language', 'arabic')->first();
            $frenchLaw = Law::where('language', 'french')->first();

            $sections = [];

            if ($englishLaw) {
                $sections['english'] = [
                    'title' => $englishLaw->title ?? '',
                    'notice' => $englishLaw->notice ?? '',
                    'sections' => $englishLaw->sections ?? [],
                ];
            }

            if ($arabicLaw) {
                $sections['arabic'] = [
                    'title' => $arabicLaw->title ?? '',
                    'notice' => $arabicLaw->notice ?? '',
                    'sections' => $arabicLaw->sections ?? [],
                ];
            }

            if ($frenchLaw) {
                $sections['french'] = [
                    'title' => $frenchLaw->title ?? '',
                    'notice' => $frenchLaw->notice ?? '',
                    'sections' => $frenchLaw->sections ?? [],
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'base_rate' => $baseRate,
                    'sections' => $sections,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching law content: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load law content: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get list of admins or gestionnaires in the same agency
     */
    public function getAgencyUsers(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent || !$agent->agency_id) {
            return response()->json([
                'success' => false,
                'message' => 'Agent profile or agency not found',
            ], 404);
        }

        $role = $request->query('role'); // 'admin' or 'gestionnaire'

        if (!in_array($role, ['admin', 'gestionnaire'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid role. Must be admin or gestionnaire',
            ], 400);
        }

        $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })
            ->where('agency_id', $agent->agency_id)
            ->select('id', 'name', 'email', 'phone')
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ];
            }),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Complain;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Mission;
use App\Models\PV;
use App\Models\PVArtwork;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:agent');
    }

    protected function currentAgent()
    {
        return Auth::user()->agent;
    }

    protected function ensureAgentOwnsPv(PV $pv): void
    {
        $agent = $this->currentAgent();

        abort_if(!$agent || $pv->agent_id !== $agent->id, 403);
    }

    public function dashboard()
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return redirect()->route('dashboard');
        }

        $user = Auth::user();
        $agency = $agent->agency;

        $pvsQuery = PV::where('agent_id', $agent->id);
        $missionQuery = Mission::where('agent_id', $agent->id);

        $stats = [
            'total' => (clone $pvsQuery)->count(),
            'open' => (clone $pvsQuery)->where('status', 'OPEN')->count(),
            'pending' => (clone $pvsQuery)->where('status', 'PENDING')->count(),
            'closed' => (clone $pvsQuery)->where('status', 'CLOSED')->count(),
            'validated' => (clone $pvsQuery)->where('payment_status', 'VALIDATED')->count(),
        ];

        $missionStats = [
            'assigned' => (clone $missionQuery)->where('status', 'ASSIGNED')->count(),
            'in_progress' => (clone $missionQuery)->where('status', 'IN_PROGRESS')->count(),
            'done' => (clone $missionQuery)->where('status', 'DONE')->count(),
        ];

        $recentPvs = (clone $pvsQuery)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $recentMissions = Mission::where('agent_id', $agent->id)
            ->orderByDesc('scheduled_at')
            ->limit(5)
            ->get();

        return view('blades.agent.dashboard', compact('agent', 'user', 'agency', 'stats', 'missionStats', 'recentPvs', 'recentMissions'));
    }

    public function profile()
    {
        $user = Auth::user();
        $agent = $user->agent;

        if (!$agent) {
            return redirect()->route('agent.dashboard');
        }

        $agent->load('agency');
        return view('blades.agent.profile', compact('user', 'agent'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $agent = $user->agent;

        if (!$agent) {
            return redirect()->route('agent.dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:255'],
            'badge_number' => ['nullable', 'string', 'max:255'],
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

        $agent->badge_number = $validated['badge_number'] ?? $agent->badge_number;
        $agent->save();

        return redirect()->route('agent.profile')->with('success', 'Profile updated successfully.');
    }

    public function viewLaw()
    {
        return view('blades.agent.law');
    }

    // Complaints System (Agents can only send complaints)
    public function complaints(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return redirect()->route('agent.dashboard');
        }
        
        $type = $request->get('type', 'all'); // all, complaint, or report
        
        // Get all items (complaints and reports) sent by this agent
        $itemsQuery = Complain::with(['admin', 'gestionnaire', 'targetUser', 'sender'])
            ->where('agent_id', $agent->id)
            ->notHiddenBy(Auth::id());
        
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
        $complaintsQuery = Complain::with(['admin', 'gestionnaire', 'targetUser', 'sender'])
            ->where('agent_id', $agent->id)
            ->where('type', Complain::TYPE_COMPLAINT)
            ->notHiddenBy(Auth::id());
        
        if ($request->filled('status')) {
            $complaintsQuery->where('status', $request->status);
        }
        
        $complaints = $complaintsQuery->orderByDesc('created_at')->paginate(20)->withQueryString();
        
        $reportsQuery = Complain::with(['admin', 'gestionnaire', 'targetUser', 'sender'])
            ->where('agent_id', $agent->id)
            ->where('type', Complain::TYPE_REPORT);
        
        if ($request->filled('status')) {
            $reportsQuery->where('status', $request->status);
        }
        
        $reports = $reportsQuery->orderByDesc('created_at')->paginate(20)->withQueryString();

        $stats = [
            'complaints_total' => Complain::where('agent_id', $agent->id)->where('type', Complain::TYPE_COMPLAINT)->count(),
            'complaints_pending' => Complain::where('agent_id', $agent->id)->where('type', Complain::TYPE_COMPLAINT)->where('status', 'PENDING')->count(),
            'reports_total' => Complain::where('agent_id', $agent->id)->where('type', Complain::TYPE_REPORT)->count(),
            'reports_pending' => Complain::where('agent_id', $agent->id)->where('type', Complain::TYPE_REPORT)->where('status', 'PENDING')->count(),
        ];

        return view('blades.agent.reports-and-complaints.index', compact('items', 'complaints', 'reports', 'agent', 'stats'));
    }

    public function complaintsInbox(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return redirect()->route('agent.dashboard');
        }
        
        $query = Complain::with(['sender', 'admin', 'gestionnaire'])
            ->where('target_role', 'agent')
            ->where('type', Complain::TYPE_COMPLAINT)
            ->notHiddenBy(Auth::id())
            ->where(function ($query) use ($agent) {
                $query->where('target_user_id', Auth::id())
                    ->orWhere(function ($sub) use ($agent) {
                        $sub->whereNull('target_user_id')
                            ->where('agency_id', $agent->agency_id);
                    });
            });
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $complaints = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.agent.complaints.inbox', compact('complaints', 'agent'));
    }

    public function complaintsSent(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return redirect()->route('agent.dashboard');
        }
        
        $query = Complain::with(['targetUser', 'admin', 'gestionnaire'])
            ->where('agent_id', $agent->id)
            ->where('type', Complain::TYPE_COMPLAINT)
            ->where('sender_user_id', Auth::id())
            ->notHiddenBy(Auth::id());
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $complaints = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.agent.complaints.sent', compact('complaints', 'agent'));
    }

    public function createComplaint()
    {
        $agent = $this->currentAgent();
        
        if (!$agent || !$agent->agency_id) {
            return redirect()->route('agent.complaints.index')->with('error', 'You must be associated with an agency.');
        }
        
        // Get agency admin and gestionnaires
        $agency = $agent->agency;
        $admin = $agency->admin;
        $gestionnaires = $agency->gestionnaires;
        
        $type = request()->get('type', 'complaint'); // complaint or report
        
        return view('blades.agent.reports-and-complaints.create', compact('admin', 'gestionnaires', 'type'));
    }

    public function showComplaint($id)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return redirect()->route('agent.dashboard');
        }

        $complaint = Complain::with(['admin', 'gestionnaire', 'targetUser', 'sender'])
            ->where('agent_id', $agent->id)
            ->where('type', Complain::TYPE_COMPLAINT)
            ->find($id);
        
        if (!$complaint) {
            return view('blades.shared.content-not-found', [
                'pageTitle' => 'Complaint Not Found',
                'title' => 'Complaint Not Found',
                'message' => 'Sorry, the complaint you are looking for does not exist or has been deleted.',
                'backUrl' => route('agent.complaints.index'),
            ]);
        }
        
        return view('blades.agent.reports-and-complaints.show', compact('complaint', 'agent'));
    }

    public function deleteComplaint($id)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return redirect()->route('agent.dashboard');
        }
        
        // Agent can delete any complaint they see in their table (complaints only, not reports)
        $complaint = Complain::where('type', Complain::TYPE_COMPLAINT)
            ->findOrFail($id);
        
        $complaint->hideForUser(Auth::id());
        
        return redirect()->route('agent.complaints.index')->with('success', 'Complaint deleted successfully');
    }

    public function respondToComplaint(Request $request, $id)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return redirect()->route('agent.dashboard');
        }

        $request->validate([
            'agent_response' => 'required|string',
        ]);

        $complaint = Complain::where('agent_id', $agent->id)
            ->where('type', Complain::TYPE_COMPLAINT)
            ->findOrFail($id);
        
        $complaint->agent_response = $request->agent_response;
        $complaint->save();

        return redirect()->route('agent.complaints.index')->with('success', 'Response sent successfully');
    }

    public function storeComplaint(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent || !$agent->agency_id) {
            return redirect()->route('agent.dashboard');
        }

        $type = $request->get('type', 'complaint'); // complaint or report
        
        // Validation rules
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'location_link' => 'nullable|url|max:255',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png',
            'target_role' => 'required|in:admin,gestionnaire',
        ], [
            'images.*.max' => 'Each image must not be larger than 10MB.',
            'images.*.image' => 'Each file must be an image.',
        ]);

        // For reports, only gestionnaire is allowed
        if ($type === 'report' && $data['target_role'] !== 'gestionnaire') {
            return redirect()->back()->withErrors(['target_role' => 'Reports can only be sent to gestionnaires.'])->withInput();
        }

        // For complaints, only admin is allowed
        if ($type === 'complaint' && $data['target_role'] !== 'admin') {
            return redirect()->back()->withErrors(['target_role' => 'Complaints can only be sent to admins.'])->withInput();
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
        $complaintType = Complain::resolveType('agent', $targetRole);
        
        // Determine target user
        $targetUserId = null;
        if ($targetRole === 'admin') {
            $targetUserId = $agent->agency->admin_id;
        } elseif ($targetRole === 'gestionnaire') {
            $gestionnaire = $agent->agency->gestionnaires->first();
            $targetUserId = $gestionnaire ? $gestionnaire->id : null;
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

        return redirect()->route('agent.complaints.index')->with('success', ucfirst($type) . ' submitted successfully');
    }

    public function viewPVs(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return redirect()->route('dashboard');
        }

        $query = PV::with(['agency', 'artworkUsages'])
            ->where('agent_id', $agent->id);
        
        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $pvs = $query->orderByDesc('created_at')->get();

        return view('blades.agent.pvs.index', compact('pvs'));
    }

    public function createPV(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return redirect()->route('dashboard');
        }

        $mission = null;
        if ($request->filled('mission_id')) {
            $mission = Mission::where('agent_id', $agent->id)->find($request->input('mission_id'));
        }

        return view('blades.agent.pvs.create', [
            'agent' => $agent,
            'baseRate' => config('artrights.base_rate', 200),
            'mission' => $mission,
        ]);
    }

    public function storePV(Request $request)
    {
        $agent = $this->currentAgent();

        if (!$agent) {
            return redirect()->route('dashboard');
        }

        $data = $request->validate([
            'mission_id' => 'nullable|exists:missions,id',
            'shop_name' => 'required|string|max:255',
            'shop_type' => 'required|string|max:255',
            'date_of_inspection' => 'required|date',
            'notes' => 'nullable|string',
            'report_files.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('report_files') && count($request->file('report_files')) > 25) {
            return back()
                ->withErrors(['report_files' => 'You can upload maximum 25 images.'])
                ->withInput();
        }

        $mission = null;
        if (!empty($data['mission_id'])) {
            $mission = Mission::where('agent_id', $agent->id)->findOrFail($data['mission_id']);
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
            'shop_name' => $data['shop_name'],
            'shop_type' => $data['shop_type'],
            'date_of_inspection' => $data['date_of_inspection'],
            'status' => 'OPEN',
            'payment_status' => 'PENDING',
            'notes' => $data['notes'] ?? null,
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

        return redirect()
            ->route('agent.pvs.show', $pv)
            ->with('success', 'PV created successfully. Add devices and artworks to finish the report.');
    }

    public function showPV(PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        $pv->load([
            'agency',
            'mission',
            'devices.deviceType',
            'artworkUsages.artwork.artist.user',
            'artworkUsages.device',
        ]);

        return view('blades.agent.pvs.show', compact('pv'));
    }

    public function addDevice(PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        $deviceTypes = DeviceType::orderBy('type')->orderBy('name')->get();

        return view('blades.agent.pvs.devices.create', compact('pv', 'deviceTypes'));
    }

    public function storeDevice(Request $request, PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        // Check if devices array exists (multiple devices) or single device
        if ($request->has('devices') && is_array($request->devices)) {
            // Multiple devices
            $validated = $request->validate([
                'devices' => 'required|array|min:1',
                'devices.*.device_type_id' => 'nullable|exists:device_types,id',
                'devices.*.name' => 'required|string|max:255',
                'devices.*.type' => 'nullable|string|max:255',
                'devices.*.coefficient' => 'required|numeric|min:0.1',
                'devices.*.quantity' => 'required|integer|min:1',
                'devices.*.notes' => 'nullable|string',
            ]);

            $createdDevices = [];
            foreach ($validated['devices'] as $deviceData) {
                $deviceType = null;
                if (!empty($deviceData['device_type_id'])) {
                    $deviceType = DeviceType::find($deviceData['device_type_id']);
                }

                $device = Device::create([
                    'pv_id' => $pv->id,
                    'device_type_id' => $deviceType?->id,
                    'name' => $deviceData['name'],
                    'type' => $deviceData['type'] ?? $deviceType?->type,
                    'coefficient' => $deviceData['coefficient'],
                    'quantity' => $deviceData['quantity'],
                    'notes' => $deviceData['notes'] ?? null,
                    'amount' => 0,
                ]);
                
                $createdDevices[] = $device->name;
            }

            $message = count($createdDevices) > 1 
                ? count($createdDevices) . ' devices added successfully: ' . implode(', ', $createdDevices)
                : 'Device ' . $createdDevices[0] . ' added successfully.';

            return redirect()
                ->route('agent.pvs.show', $pv)
                ->with('success', $message);
        } else {
            // Single device (backward compatibility)
            $data = $request->validate([
                'device_type_id' => 'nullable|exists:device_types,id',
                'name' => 'nullable|string|max:255|required_without:device_type_id',
                'type' => 'nullable|string|max:255',
                'coefficient' => 'nullable|numeric|min:0.1',
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string',
            ]);

            $deviceType = null;
            if (!empty($data['device_type_id'])) {
                $deviceType = DeviceType::find($data['device_type_id']);
            }

            $device = Device::create([
                'pv_id' => $pv->id,
                'device_type_id' => $deviceType?->id,
                'name' => $data['name'] ?? $deviceType?->name,
                'type' => $data['type'] ?? $deviceType?->type,
                'coefficient' => $data['coefficient'] ?? $deviceType->coefficient ?? 1,
                'quantity' => $data['quantity'],
                'notes' => $data['notes'] ?? null,
                'amount' => 0,
            ]);

            return redirect()
                ->route('agent.pvs.show', $pv)
                ->with('success', 'Device ' . $device->name . ' added successfully.');
        }
    }

    public function removeDevice(PV $pv, Device $device)
    {
        $this->ensureAgentOwnsPv($pv);
        abort_if($device->pv_id !== $pv->id, 403);

        if ($device->usages()->exists()) {
            return redirect()
                ->back()
                ->withErrors(['device' => 'This device is linked to artworks. Remove the artworks first.']);
        }

        $device->delete();

        return redirect()
            ->route('agent.pvs.show', $pv)
            ->with('success', 'Device removed successfully.');
    }

    public function addArtwork(PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        $agenciesQuery = Agency::whereHas('artists', function ($query) {
            $query->where('status', 'APPROVED');
        });

        if ($pv->agency_id) {
            $agenciesQuery->orWhere('id', $pv->agency_id);
        }

        $agencies = $agenciesQuery
            ->orderBy('wilaya')
            ->orderBy('agency_name')
            ->get()
            ->unique('id')
            ->values();

        $devices = $pv->devices()->orderBy('name')->get();

        return view('blades.agent.pvs.artworks.create', compact('pv', 'agencies', 'devices'));
    }

    public function getArtistsByAgency(Request $request, PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        $request->validate([
            'agency_id' => 'required|exists:agencies,id',
        ]);

        $artists = Artist::where('agency_id', $request->agency_id)
            ->where('status', 'APPROVED')
            ->with('user')
            ->orderBy('stage_name')
            ->get();

        return response()->json($artists->map(function($artist) {
            return [
                'id' => $artist->id,
                'name' => $artist->user->name ?? $artist->stage_name ?? 'Unknown',
                'stage_name' => $artist->stage_name,
            ];
        }));
    }

    public function getArtworksByArtist(Request $request, PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        $request->validate([
            'artist_id' => 'required|exists:artists,id',
        ]);

        $artworks = Artwork::where('artist_id', $request->artist_id)
            ->where('status', 'APPROVED')
            ->where('platform_tax_status', 'PAID')
            ->with('category')
            ->orderBy('title')
            ->get();

        return response()->json($artworks->map(function($artwork) {
            return [
                'id' => $artwork->id,
                'title' => $artwork->title,
                'category' => $artwork->category->name ?? 'N/A',
                'category_coefficient' => $artwork->category->coefficient ?? 1,
            ];
        }));
    }

    public function storeArtwork(Request $request, PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        $data = $request->validate([
            'artwork_id' => 'required|exists:artworks,id',
            'device_id' => 'nullable|exists:devices,id',
            'calculation_method' => 'required|in:hours,count',
            'hours_used' => 'required_if:calculation_method,hours|numeric|min:0.5',
            'usage_count' => 'required_if:calculation_method,count|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $artwork = Artwork::with(['category', 'artist.user'])->findOrFail($data['artwork_id']);

        if ($artwork->status !== 'APPROVED') {
            return redirect()
                ->back()
                ->withErrors(['artwork_id' => 'Only approved artworks can be used in PV calculations.']);
        }

        if ($artwork->platform_tax_status !== 'PAID') {
            return redirect()
                ->back()
                ->withErrors(['artwork_id' => 'This artwork cannot be used in PVs until the platform tax is paid by the artist.']);
        }
        $device = null;

        if (!empty($data['device_id'])) {
            $device = Device::where('pv_id', $pv->id)->findOrFail($data['device_id']);
        }

        // Determine time value based on calculation method
        $timeValue = $data['calculation_method'] === 'hours' 
            ? $data['hours_used'] 
            : $data['usage_count'];
        
        $fine = $this->calculateFine(
            $artwork->category?->coefficient ?? 1,
            $device?->coefficient ?? 1,
            $timeValue,
            1, // plays_count is no longer used in calculation
            $pv
        );

        $pvArtwork = PVArtwork::create([
            'pv_id' => $pv->id,
            'artwork_id' => $artwork->id,
            'device_id' => $device?->id,
            'hours_used' => $data['calculation_method'] === 'hours' ? $data['hours_used'] : ($data['usage_count'] ?? 1),
            'plays_count' => 1, // Default value, not used in calculation
            'base_rate' => $pv->base_rate,
            'fine_amount' => $fine,
            'notes' => $data['notes'] ?? null,
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

        return redirect()
            ->route('agent.pvs.show', $pv)
            ->with('success', 'Artwork "' . $pvArtwork->artwork->title . '" was added to this PV.');
    }

    public function removeArtwork(PV $pv, PVArtwork $pvArtwork)
    {
        $this->ensureAgentOwnsPv($pv);
        abort_if($pvArtwork->pv_id !== $pv->id, 403);

        $device = $pvArtwork->device;
        $pvArtwork->delete();

        $pv->recalculateTotals();
        $this->updateDeviceAmount($device);

        return redirect()
            ->route('agent.pvs.show', $pv)
            ->with('success', 'Artwork usage removed.');
    }

    public function closePV(Request $request, PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);
        $agent = $this->currentAgent();

        if ($pv->isFinalized()) {
            return redirect()
                ->back()
                ->withErrors(['pv' => 'This PV has already been finalized by the agency.']);
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

        return redirect()
            ->route('agent.pvs.show', $pv)
            ->with('success', 'PV closed successfully. Waiting for agency finalization.');
    }

    public function updatePayment(Request $request, PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        $data = $request->validate([
            'payment_method' => 'required|in:CASH,CHEQUE',
            'cash_received_amount' => 'nullable|numeric|min:0',
            'agent_payment_confirmed' => 'nullable|boolean',
        ]);

        $pv->payment_method = $data['payment_method'];
        
        // Agent confirms payment received from client
        if (isset($data['agent_payment_confirmed']) && $data['agent_payment_confirmed']) {
            $pv->agent_payment_confirmed = true;
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
        }
        
        if (isset($data['cash_received_amount'])) {
            $pv->cash_received_amount = $data['cash_received_amount'];
        }
        
        $pv->save();

        return redirect()
            ->route('agent.pvs.show', $pv)
            ->with('success', 'Payment information updated.');
    }

    public function uploadPaymentProof(Request $request, PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        $data = $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);

        if ($pv->payment_proof_path) {
            Storage::disk('public')->delete($pv->payment_proof_path);
        }

        $pv->payment_proof_path = $request->file('payment_proof')->store('payment_proofs', 'public');
        $pv->save();

        return redirect()
            ->route('agent.pvs.show', $pv)
            ->with('success', 'Payment proof uploaded successfully.');
    }

    public function uploadPhotos(Request $request, PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        if ($pv->status !== 'OPEN') {
            return redirect()
                ->back()
                ->withErrors(['photos' => 'Photos can only be uploaded to OPEN PVs.']);
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
                return redirect()
                    ->back()
                    ->withErrors(['photos' => 'You can upload up to 100 photos. Currently you have ' . $currentCount . ' photos.']);
            }

            $newPaths = [];
            foreach ($files as $file) {
                $newPaths[] = $file->store('pv_reports', 'public');
            }

            $allPaths = array_merge($existingFiles, $newPaths);
            $pv->file_path = json_encode($allPaths);
            $pv->save();

            return redirect()
                ->route('agent.pvs.show', $pv)
                ->with('success', count($newPaths) . ' photo(s) uploaded successfully.');
        }

        return redirect()->back()->withErrors(['photos' => 'No photos were uploaded.']);
    }

    public function printMission(Mission $mission)
    {
        $agent = $this->currentAgent();
        
        if (!$agent || $mission->agent_id !== $agent->id) {
            abort(403);
        }

        $mission->load(['agent.user', 'agency', 'gestionnaire']);
        
        $pv = null;
        if ($mission->pv) {
            $pv = $mission->pv;
            $pv->load([
                'agency',
                'agent.user',
                'devices.deviceType',
                'artworkUsages.artwork.artist.user',
                'artworkUsages.device',
            ]);
        }

        return view('blades.agent.missions.print', compact('mission', 'pv'));
    }

    public function printPV(PV $pv)
    {
        $this->ensureAgentOwnsPv($pv);

        $pv->load([
            'agency',
            'mission',
            'agent.user',
            'devices.deviceType',
            'artworkUsages.artwork.artist.user',
            'artworkUsages.device',
        ]);

        return view('blades.agent.pvs.print', compact('pv'));
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

        // Correct calculation: category_coefficient × device_coefficient × hours × base_rate
        // plays_count is no longer used in the calculation
        return round($categoryCoefficient * $deviceCoefficient * max($hours, 0.5) * $baseRate, 2);
    }

}


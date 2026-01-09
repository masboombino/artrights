<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Complain;
use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class MissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:gestionnaire');
    }

    public function index(Request $request)
    {
        $gestionnaire = Auth::user();
        $query = Mission::with(['agent.user', 'pv'])
            ->where('agency_id', $gestionnaire->agency_id);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $missions = $query->orderByDesc('scheduled_at')->paginate(10)->withQueryString();

        return view('blades.gestionnaire.missions.index', compact('missions'));
    }

    public function create(Request $request)
    {
        $gestionnaire = Auth::user();
        $agents = Agent::with('user')
            ->where('agency_id', $gestionnaire->agency_id)
            ->get();

        $complaint = null;
        if ($request->filled('complaint_id')) {
            $complaint = Complain::with(['artist.user'])
                ->whereHas('artist', function ($query) use ($gestionnaire) {
                    $query->where('agency_id', $gestionnaire->agency_id);
                })
                ->find($request->input('complaint_id'));
        }

        return view('blades.gestionnaire.missions.create', compact('agents', 'complaint'));
    }

    public function store(Request $request)
    {
        $gestionnaire = Auth::user();

        $data = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location_text' => 'nullable|string|max:255',
            'map_link' => 'nullable|url',
            'scheduled_at' => 'nullable|date',
            'complaint_id' => 'nullable|exists:complaints,id',
        ]);

        $agent = Agent::where('agency_id', $gestionnaire->agency_id)->findOrFail($data['agent_id']);

        $complaint = null;
        if (!empty($data['complaint_id'])) {
            $complaint = Complain::whereHas('artist', function ($query) use ($gestionnaire) {
                    $query->where('agency_id', $gestionnaire->agency_id);
                })
                ->findOrFail($data['complaint_id']);
        }

        $mission = Mission::create([
            'agency_id' => $gestionnaire->agency_id,
            'gestionnaire_id' => $gestionnaire->id,
            'agent_id' => $agent->id,
            'complaint_id' => $complaint?->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? ($complaint ? 'Mission created for complaint #' . $complaint->id : null),
            'location_text' => $data['location_text'] ?? null,
            'map_link' => $data['map_link'] ?? ($complaint->location_link ?? null),
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'status' => 'ASSIGNED',
        ]);

        if ($complaint) {
            $complaint->status = 'IN_PROGRESS';
            $complaint->mission_id = $mission->id;
            $complaint->save();
        }

        $agent->loadMissing('user');
        if ($agent->user) {
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

        return redirect()->route('gestionnaire.missions.index')->with('success', 'Mission created successfully.');
    }

    public function show($id)
    {
        $gestionnaire = Auth::user();
        $mission = Mission::with(['agent.user', 'pv'])
            ->where('agency_id', $gestionnaire->agency_id)
            ->findOrFail($id);

        // Get agents for assignment if mission doesn't have an agent
        $agents = null;
        if (!$mission->agent_id) {
            $agents = Agent::with('user')
                ->where('agency_id', $gestionnaire->agency_id)
                ->get();
        }

        return view('blades.gestionnaire.missions.show', compact('mission', 'agents'));
    }

    public function updateStatus(Request $request, $id)
    {
        $gestionnaire = Auth::user();
        $mission = Mission::where('agency_id', $gestionnaire->agency_id)->findOrFail($id);

        $data = $request->validate([
            'status' => 'required|in:ASSIGNED,IN_PROGRESS,DONE,CANCELLED',
        ]);

        $mission->status = $data['status'];
        $mission->save();

        return redirect()->back()->with('success', 'Mission updated.');
    }

    public function assignAgent(Request $request, $id)
    {
        $gestionnaire = Auth::user();
        $mission = Mission::where('agency_id', $gestionnaire->agency_id)->findOrFail($id);

        $data = $request->validate([
            'agent_id' => 'required|exists:agents,id',
        ]);

        $agent = Agent::where('agency_id', $gestionnaire->agency_id)->findOrFail($data['agent_id']);

        $mission->agent_id = $agent->id;
        $mission->status = 'ASSIGNED';
        $mission->save();

        // Send notification to agent
        $agent->loadMissing('user');
        if ($agent->user) {
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

        return redirect()->back()->with('success', 'Agent assigned successfully.');
    }

    public function printMission($id)
    {
        $gestionnaire = Auth::user();
        $mission = Mission::with(['agent.user', 'agency', 'gestionnaire', 'pv'])
            ->where('agency_id', $gestionnaire->agency_id)
            ->findOrFail($id);

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
}


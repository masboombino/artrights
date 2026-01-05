<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MissionController extends Controller
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

    public function index(Request $request)
    {
        $agent = $this->currentAgent();
        $query = Mission::with('pv')
            ->where('agent_id', $agent->id);
        
        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $missions = $query->orderByDesc('scheduled_at')->get();

        return view('blades.agent.missions.index', compact('missions'));
    }

    public function show($id)
    {
        $agent = $this->currentAgent();
        $mission = Mission::with(['pv', 'pv.agency', 'pv.agent.user'])
            ->where('agent_id', $agent->id)
            ->findOrFail($id);

        return view('blades.agent.missions.show', compact('mission'));
    }

    public function updateStatus(Request $request, $id)
    {
        $agent = $this->currentAgent();
        $mission = Mission::where('agent_id', $agent->id)->findOrFail($id);

        $data = $request->validate([
            'status' => 'required|in:ASSIGNED,IN_PROGRESS,DONE,CANCELLED',
        ]);

        $mission->status = $data['status'];
        $mission->save();

        return redirect()->back()->with('success', 'Mission status updated.');
    }
}


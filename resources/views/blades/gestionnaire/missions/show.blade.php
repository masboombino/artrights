<x-allthepages-layout pageTitle="Mission Details">
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded p-4" style="background-color: #d1fae5; color: #065f46;">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <h1 class="text-2xl font-bold" style="color: #193948;">{{ $mission->title }}</h1>
                    <p style="color: #193948;">Assigned Agent: {{ $mission->agent->user->name ?? 'Not assigned yet' }}</p>
                    <p style="color: #193948;">Schedule: {{ $mission->scheduled_at ? $mission->scheduled_at->format('d/m/Y H:i') : 'Not set' }}</p>
                    <p style="color: #193948;">Location: {{ $mission->location_text ?? 'Not provided' }}</p>
                    @if($mission->map_link)
                        <p><a href="{{ $mission->map_link }}" target="_blank" style="color: #D6BFBF;">View map</a></p>
                    @endif
                </div>
                <div>
                    <form method="POST" action="{{ route('gestionnaire.missions.update-status', $mission->id) }}">
                        @csrf
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Status</label>
                        <select name="status" class="rounded border p-2">
                            @foreach(['ASSIGNED','IN_PROGRESS','DONE','CANCELLED'] as $status)
                                <option value="{{ $status }}" @selected($mission->status === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="ml-2 rounded px-3 py-2 text-sm font-semibold" style="background-color: #193948; color: #4FADC0;">Update</button>
                    </form>
                    <div style="margin-top: 1rem; text-align: center;">
                        <a href="{{ route('gestionnaire.missions.print', $mission->id) }}" target="_blank" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #4FADC0; color: #193948; display: inline-block; width: 100%; text-decoration: none;">
                            🖨️ Print Mission
                        </a>
                    </div>
                </div>
            </div>
            @if($mission->description)
                <div class="mt-4">
                    <p style="color: #193948;">{{ $mission->description }}</p>
                </div>
            @endif
        </div>

        @if(!$mission->agent_id && $agents)
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD; border: 2px solid #193948;">
            <h2 class="text-xl font-semibold mb-4" style="color: #193948;">Assign Agent to Mission</h2>
            <form method="POST" action="{{ route('gestionnaire.missions.assign-agent', $mission->id) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" style="color: #193948;">Select Agent</label>
                    <select name="agent_id" class="w-full rounded border p-2" required style="border-color: #193948;">
                        <option value="">Choose an agent...</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->user->name ?? 'Agent #' . $agent->id }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #193948; color: #4FADC0;">
                    Assign Agent
                </button>
            </form>
        </div>
        @endif

        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-xl font-semibold mb-4" style="color: #193948;">Related PV</h2>
            @if($mission->pv)
                <div class="flex items-center justify-between">
                    <div>
                        <p style="color: #193948;">PV ID: #{{ $mission->pv->id }}</p>
                        <p style="color: #193948;">Shop: {{ $mission->pv->shop_name }}</p>
                        <p style="color: #193948;">Status: {{ $mission->pv->status }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('gestionnaire.pvs.show', $mission->pv->id) }}" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948;">
                            View PV
                        </a>
                        @if($mission->pv->status === 'CLOSED')
                            <a href="{{ route('gestionnaire.missions.print', $mission->id) }}" target="_blank" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #4FADC0; color: #193948;">
                                🖨️ Print Mission
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <p style="color: #193948;">PV not created yet.</p>
            @endif
        </div>
    </div>
</x-allthepages-layout>


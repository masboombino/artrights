<x-allthepages-layout pageTitle="Assign Mission">
    <div class="space-y-6">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            @if(isset($complaint))
                <div class="mb-6 rounded border p-4" style="border-color: #193948;">
                    <h2 class="text-xl font-semibold mb-2" style="color: #193948;">Complaint Reference</h2>
                    <p style="color: #193948;"><strong>Subject:</strong> {{ $complaint->subject }}</p>
                    <p style="color: #193948;"><strong>Artist:</strong> {{ $complaint->artist->user->name ?? 'N/A' }}</p>
                    @if($complaint->location_link)
                        <p style="color: #193948;"><strong>Location:</strong> <a href="{{ $complaint->location_link }}" target="_blank" style="color: #D6BFBF;">Open Map</a></p>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('gestionnaire.missions.store') }}" class="space-y-4">
                @csrf
                @if(isset($complaint))
                    <input type="hidden" name="complaint_id" value="{{ $complaint->id }}">
                @endif

                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Agent</label>
                    <select name="agent_id" class="w-full rounded border p-2" required>
                        <option value="">Select agent</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" @selected(old('agent_id') == $agent->id)>{{ $agent->user->name ?? 'Agent #' . $agent->id }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Title</label>
                    <input type="text" name="title" class="w-full rounded border p-2" value="{{ old('title', isset($complaint) ? 'Mission for complaint #' . $complaint->id : '') }}" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Description</label>
                    <textarea name="description" rows="4" class="w-full rounded border p-2">{{ old('description', $complaint->message ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Location</label>
                        <input type="text" name="location_text" class="w-full rounded border p-2" value="{{ old('location_text') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Map Link</label>
                        <input type="url" name="map_link" class="w-full rounded border p-2" placeholder="https://maps.google.com/..." value="{{ old('map_link', $complaint->location_link ?? '') }}">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Scheduled At</label>
                    <input type="datetime-local" name="scheduled_at" class="w-full rounded border p-2" value="{{ old('scheduled_at') }}">
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('gestionnaire.missions.index') }}" class="rounded border px-4 py-2 font-semibold" style="color: #193948; border-color: #193948;">Cancel</a>
                    <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #193948; color: #4FADC0;">Save Mission</button>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>


<x-allthepages-layout pageTitle="Assign Mission">
    <div class="space-y-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold" style="color: #D6BFBF;">Assign Mission</h1>
            <a href="{{ route('admin.manage-missions') }}" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem;">
                <span>Back</span>
            </a>
        </div>

        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948;">
            <form action="{{ route('admin.store-mission') }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <div class="p-4 rounded-lg mb-4" style="background-color: rgba(214, 191, 191, 0.1); border: 2px solid #D6BFBF;">
                        <p class="text-sm font-semibold mb-3" style="color: #193948;">Select who to assign this mission to (at least one is required):</p>
                        
                        <div class="mb-4">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" name="assign_to_gestionnaire" id="assign_to_gestionnaire" value="1" 
                                    @checked(old('assign_to_gestionnaire') || old('gestionnaire_id'))
                                    onchange="toggleGestionnaireSelect()"
                                    class="mr-2">
                                <label for="assign_to_gestionnaire" class="text-sm font-medium" style="color: #193948;">Assign to Gestionnaire</label>
                            </div>
                            <select name="gestionnaire_id" id="gestionnaire_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2"
                                style="background-color: white; color: #193948;"
                                @if(!old('assign_to_gestionnaire') && !old('gestionnaire_id')) disabled @endif>
                                <option value="">Select gestionnaire</option>
                                @foreach($gestionnaires as $gestionnaire)
                                    <option value="{{ $gestionnaire->id }}" @selected(old('gestionnaire_id') == $gestionnaire->id)>
                                        {{ $gestionnaire->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gestionnaire_id')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center mb-2">
                                <input type="checkbox" name="assign_to_agent" id="assign_to_agent" value="1"
                                    @checked(old('assign_to_agent') || old('agent_id'))
                                    onchange="toggleAgentSelect()"
                                    class="mr-2">
                                <label for="assign_to_agent" class="text-sm font-medium" style="color: #193948;">Assign to Agent</label>
                            </div>
                            <select name="agent_id" id="agent_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2"
                                style="background-color: white; color: #193948;"
                                @if(!old('assign_to_agent') && !old('agent_id')) disabled @endif>
                                <option value="">Select agent</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" @selected(old('agent_id') == $agent->id)>
                                        {{ $agent->user->name ?? 'Agent #' . $agent->id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agent_id')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium mb-2" style="color: #193948;">Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">
                        @error('title')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium mb-2" style="color: #193948;">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-3 rounded-lg" style="background-color: rgba(214, 191, 191, 0.1); border: 1px solid #D6BFBF;">
                        <p class="text-xs mb-3" style="color: #193948; font-style: italic;">Optional: Location information (only if this mission requires going to a location)</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="location_text" class="block text-sm font-medium mb-2" style="color: #193948;">Location</label>
                                <input type="text" name="location_text" id="location_text" value="{{ old('location_text') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    style="background-color: white; color: #193948;"
                                    placeholder="e.g., Shop name, Address">
                                @error('location_text')
                                    <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="map_link" class="block text-sm font-medium mb-2" style="color: #193948;">Map Link</label>
                                <input type="url" name="map_link" id="map_link" value="{{ old('map_link') }}" placeholder="https://maps.google.com/..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    style="background-color: white; color: #193948;">
                                @error('map_link')
                                    <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium mb-2" style="color: #193948;">Scheduled At (Optional)</label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">
                        <p class="mt-1 text-xs" style="color: #36454f;">Leave empty if no specific date/time is required</p>
                        @error('scheduled_at')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-4 mt-6">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem; border: none; cursor: pointer; font-weight: 600;">
                        <span>Assign Mission</span>
                    </button>
                    <a href="{{ route('admin.manage-missions') }}" class="rounded transition hover:opacity-90" style="background-color: #36454f; color: #4FADC0; padding: 0.75rem 1.5rem; text-decoration: none; font-weight: 600;">
                        <span>Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleGestionnaireSelect() {
            const checkbox = document.getElementById('assign_to_gestionnaire');
            const select = document.getElementById('gestionnaire_id');
            select.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                select.value = '';
            }
        }

        function toggleAgentSelect() {
            const checkbox = document.getElementById('assign_to_agent');
            const select = document.getElementById('agent_id');
            select.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                select.value = '';
            }
        }

        // Validate form before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const gestionnaireCheckbox = document.getElementById('assign_to_gestionnaire');
            const agentCheckbox = document.getElementById('assign_to_agent');
            const gestionnaireSelect = document.getElementById('gestionnaire_id');
            const agentSelect = document.getElementById('agent_id');

            if (!gestionnaireCheckbox.checked && !agentCheckbox.checked) {
                e.preventDefault();
                alert('Please select at least one recipient (Gestionnaire or Agent)');
                return false;
            }

            if (gestionnaireCheckbox.checked && !gestionnaireSelect.value) {
                e.preventDefault();
                alert('Please select a gestionnaire');
                return false;
            }

            if (agentCheckbox.checked && !agentSelect.value) {
                e.preventDefault();
                alert('Please select an agent');
                return false;
            }
        });
    </script>
</x-allthepages-layout>


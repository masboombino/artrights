<x-allthepages-layout pageTitle="Agency Details">
    <div class="space-y-6" style="padding: 2rem;">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-bold mb-2" style="color: #D6BFBF;">{{ $agency->agency_name }}</h1>
            <p class="text-2xl font-semibold mb-4" style="color: #D6BFBF;">{{ $agency->wilaya }}</p>
            <a href="{{ route('superadmin.manage-agencies') }}" class="inline-block rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem;">
                <span>Back to Agencies</span>
            </a>
        </div>

        <div class="rounded-lg shadow-lg p-6 mb-6 text-center" style="background-color: #F3EBDD; border: 3px solid #193948;">
            <h2 class="text-2xl font-bold mb-6" style="color: #D6BFBF;">Statistics</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-4xl font-bold mb-2" style="color: #193948;">{{ $transactionsCount }}</p>
                    <p class="text-base font-semibold" style="color: #D6BFBF;">Transactions</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold mb-2" style="color: #193948;">{{ $gestionnaires->count() }}</p>
                    <p class="text-base font-semibold" style="color: #D6BFBF;">Gestionnaires</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold mb-2" style="color: #193948;">{{ $agents->count() }}</p>
                    <p class="text-base font-semibold" style="color: #D6BFBF;">Agents</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold mb-2" style="color: #193948;">{{ $artists->count() }}</p>
                    <p class="text-base font-semibold" style="color: #D6BFBF;">Artists</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD;">
                <div class="p-4 flex justify-between items-center" style="background-color: #193948;">
                    <h3 class="text-xl font-bold" style="color: #D6BFBF;">Admin</h3>
                    <div class="flex gap-2">
                        @if($agency->admin)
                            <form action="{{ route('superadmin.remove-agency-admin', $agency->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this admin from the agency?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded text-xs transition hover:opacity-90" style="background-color: #E76268; color: #193948; padding: 0.5rem 1rem;">
                                    Remove
                                </button>
                            </form>
                            <button onclick="showTransferAdminModal()" class="rounded text-xs transition hover:opacity-90" style="background-color: #4FADC0; color: #193948; padding: 0.5rem 1rem;">
                                Transfer
                            </button>
                        @endif
                        <a href="{{ route('superadmin.assign-agency-admin', $agency->id) }}" class="rounded text-xs transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.5rem 1rem;">
                            {{ $agency->admin ? 'Change' : 'Assign' }}
                        </a>
                    </div>
                </div>
                <div class="p-6 text-center">
                    @if($agency->admin)
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-bold mb-1" style="color: #D6BFBF;">Name:</p>
                                <p class="text-base" style="color: #193948;">{{ $agency->admin->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-bold mb-1" style="color: #D6BFBF;">Email:</p>
                                <p class="text-base" style="color: #193948;">{{ $agency->admin->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-bold mb-1" style="color: #D6BFBF;">Phone:</p>
                                <p class="text-base" style="color: #193948;">{{ $agency->admin->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-base" style="color: #193948;">No admin assigned</p>
                    @endif
                </div>
            </div>

            <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD;">
                <div class="p-4 flex justify-between items-center" style="background-color: #193948;">
                    <h3 class="text-xl font-bold" style="color: #D6BFBF;">Gestionnaires ({{ $gestionnaires->count() }})</h3>
                    <a href="{{ route('superadmin.create-agency-gestionnaire', $agency->id) }}" class="rounded text-xs transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.5rem 1rem;">
                        Add
                    </a>
                </div>
                <div class="p-4 max-h-64 overflow-y-auto">
                    @forelse($gestionnaires as $gestionnaire)
                        <div class="mb-4 pb-4 border-b text-center" style="border-color: #193948;">
                            <p class="text-base font-bold mb-1" style="color: #193948;">{{ $gestionnaire->name }}</p>
                            <p class="text-sm mb-3" style="color: #193948;">{{ $gestionnaire->email }}</p>
                            <div class="flex gap-2 justify-center">
                                <button onclick="showTransferGestionnaireModal({{ $gestionnaire->id }}, '{{ addslashes($gestionnaire->name) }}')" class="rounded text-xs transition hover:opacity-90" style="background-color: #4FADC0; color: #193948; padding: 0.5rem 1rem;">
                                    Transfer
                                </button>
                                <form action="{{ route('superadmin.remove-agency-gestionnaire', [$agency->id, $gestionnaire->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this gestionnaire?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded text-xs transition hover:opacity-90" style="background-color: #E76268; color: #193948; padding: 0.5rem 1rem;">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-base text-center" style="color: #193948;">No gestionnaires</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD;">
                <div class="p-4 flex justify-between items-center" style="background-color: #193948;">
                    <h3 class="text-xl font-bold" style="color: #D6BFBF;">Agents ({{ $agents->count() }})</h3>
                    <a href="{{ route('superadmin.create-agency-agent', $agency->id) }}" class="rounded text-xs transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.5rem 1rem;">
                        Add
                    </a>
                </div>
                <div class="p-4 max-h-64 overflow-y-auto">
                    @forelse($agents as $agent)
                        <div class="mb-4 pb-4 border-b text-center" style="border-color: #193948;">
                            <p class="text-base font-bold mb-1" style="color: #193948;">{{ $agent->user->name ?? 'N/A' }}</p>
                            <p class="text-sm mb-1" style="color: #193948;">{{ $agent->user->email ?? 'N/A' }}</p>
                            <p class="text-sm font-semibold mb-3" style="color: #D6BFBF;">Badge: {{ $agent->badge_number }}</p>
                            <div class="flex gap-2 justify-center">
                                <button onclick="showTransferAgentModal({{ $agent->id }}, '{{ addslashes($agent->user->name ?? 'N/A') }}')" class="rounded text-xs transition hover:opacity-90" style="background-color: #4FADC0; color: #193948; padding: 0.5rem 1rem;">
                                    Transfer
                                </button>
                                <form action="{{ route('superadmin.remove-agency-agent', [$agency->id, $agent->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this agent?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded text-xs transition hover:opacity-90" style="background-color: #E76268; color: #193948; padding: 0.5rem 1rem;">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-base text-center" style="color: #193948;">No agents</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD;">
                <div class="p-4 flex justify-between items-center" style="background-color: #193948;">
                    <h3 class="text-xl font-bold" style="color: #D6BFBF;">Artists ({{ $artists->count() }})</h3>
                </div>
                <div class="p-4 max-h-64 overflow-y-auto">
                    @forelse($artists as $artist)
                        <div class="mb-4 pb-4 border-b text-center" style="border-color: #193948;">
                            <p class="text-base font-bold mb-1" style="color: #193948;">{{ $artist->user->name ?? 'N/A' }}</p>
                            <p class="text-sm mb-1" style="color: #193948;">{{ $artist->stage_name ?? 'N/A' }}</p>
                            <p class="text-sm mb-2" style="color: #193948;">{{ $artist->user->email ?? 'N/A' }}</p>
                            <p class="text-sm font-semibold mb-1" style="color: #D6BFBF;">Status:</p>
                            <span class="px-3 py-1 rounded text-sm inline-block mb-3" style="background-color: #193948; color: #4FADC0;">
                                {{ $artist->status }}
                            </span>
                            <div class="flex gap-2 justify-center">
                                <button onclick="showTransferArtistModal({{ $artist->id }}, '{{ addslashes($artist->user->name ?? 'N/A') }}')" class="rounded text-xs transition hover:opacity-90" style="background-color: #4FADC0; color: #193948; padding: 0.5rem 1rem;">
                                    Transfer
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-base text-center" style="color: #193948;">No artists</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer Admin Modal -->
    @if($agency->admin)
    <div id="transferAdminModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948; max-width: 500px; width: 90%;">
            <h3 class="text-2xl font-bold mb-4" style="color: #193948;">Transfer Admin: {{ $agency->admin->name }}</h3>
            <form action="{{ route('superadmin.transfer-agency-admin', $agency->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="new_agency_id" class="block text-sm font-medium mb-2" style="color: #193948;">Select Target Agency</label>
                    <select name="new_agency_id" id="new_agency_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                        <option value="">-- Select Agency --</option>
                        @foreach($allAgencies as $targetAgency)
                            <option value="{{ $targetAgency->id }}">
                                {{ $targetAgency->agency_name }} - {{ $targetAgency->wilaya }}
                                @if($targetAgency->admin_id)
                                    (Has admin: {{ $targetAgency->admin->name }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 0.75rem 1.5rem;">
                        Transfer
                    </button>
                    <button type="button" onclick="hideTransferAdminModal()" class="rounded transition hover:opacity-90" style="background-color: #6b7280; color: white; padding: 0.75rem 1.5rem;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Transfer Gestionnaire Modal -->
    <div id="transferGestionnaireModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948; max-width: 500px; width: 90%;">
            <h3 class="text-2xl font-bold mb-4" style="color: #193948;">Transfer Gestionnaire: <span id="gestionnaireName"></span></h3>
            <form id="transferGestionnaireForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="gestionnaire_new_agency_id" class="block text-sm font-medium mb-2" style="color: #193948;">Select Target Agency</label>
                    <select name="new_agency_id" id="gestionnaire_new_agency_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                        <option value="">-- Select Agency --</option>
                        @foreach($allAgencies as $targetAgency)
                            <option value="{{ $targetAgency->id }}">
                                {{ $targetAgency->agency_name }} - {{ $targetAgency->wilaya }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 0.75rem 1.5rem;">
                        Transfer
                    </button>
                    <button type="button" onclick="hideTransferGestionnaireModal()" class="rounded transition hover:opacity-90" style="background-color: #6b7280; color: white; padding: 0.75rem 1.5rem;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transfer Agent Modal -->
    <div id="transferAgentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948; max-width: 500px; width: 90%;">
            <h3 class="text-2xl font-bold mb-4" style="color: #193948;">Transfer Agent: <span id="agentName"></span></h3>
            <form id="transferAgentForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="agent_new_agency_id" class="block text-sm font-medium mb-2" style="color: #193948;">Select Target Agency</label>
                    <select name="new_agency_id" id="agent_new_agency_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                        <option value="">-- Select Agency --</option>
                        @foreach($allAgencies as $targetAgency)
                            <option value="{{ $targetAgency->id }}">
                                {{ $targetAgency->agency_name }} - {{ $targetAgency->wilaya }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 0.75rem 1.5rem;">
                        Transfer
                    </button>
                    <button type="button" onclick="hideTransferAgentModal()" class="rounded transition hover:opacity-90" style="background-color: #6b7280; color: white; padding: 0.75rem 1.5rem;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transfer Artist Modal -->
    <div id="transferArtistModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948; max-width: 500px; width: 90%;">
            <h3 class="text-2xl font-bold mb-4" style="color: #193948;">Transfer Artist: <span id="artistName"></span></h3>
            <form id="transferArtistForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="artist_new_agency_id" class="block text-sm font-medium mb-2" style="color: #193948;">Select Target Agency</label>
                    <select name="new_agency_id" id="artist_new_agency_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                        <option value="">-- Select Agency --</option>
                        @foreach($allAgencies as $targetAgency)
                            <option value="{{ $targetAgency->id }}">
                                {{ $targetAgency->agency_name }} - {{ $targetAgency->wilaya }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 0.75rem 1.5rem;">
                        Transfer
                    </button>
                    <button type="button" onclick="hideTransferArtistModal()" class="rounded transition hover:opacity-90" style="background-color: #6b7280; color: white; padding: 0.75rem 1.5rem;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showTransferAdminModal() {
            document.getElementById('transferAdminModal').style.display = 'flex';
        }
        
        function hideTransferAdminModal() {
            document.getElementById('transferAdminModal').style.display = 'none';
        }

        function showTransferGestionnaireModal(gestionnaireId, gestionnaireName) {
            document.getElementById('gestionnaireName').textContent = gestionnaireName;
            document.getElementById('transferGestionnaireForm').action = '{{ route("superadmin.transfer-agency-gestionnaire", [$agency->id, ":id"]) }}'.replace(':id', gestionnaireId);
            document.getElementById('transferGestionnaireModal').style.display = 'flex';
        }
        
        function hideTransferGestionnaireModal() {
            document.getElementById('transferGestionnaireModal').style.display = 'none';
        }

        function showTransferAgentModal(agentId, agentName) {
            document.getElementById('agentName').textContent = agentName;
            document.getElementById('transferAgentForm').action = '{{ route("superadmin.transfer-agency-agent", [$agency->id, ":id"]) }}'.replace(':id', agentId);
            document.getElementById('transferAgentModal').style.display = 'flex';
        }
        
        function hideTransferAgentModal() {
            document.getElementById('transferAgentModal').style.display = 'none';
        }

        function showTransferArtistModal(artistId, artistName) {
            document.getElementById('artistName').textContent = artistName;
            document.getElementById('transferArtistForm').action = '{{ route("superadmin.transfer-agency-artist", [$agency->id, ":id"]) }}'.replace(':id', artistId);
            document.getElementById('transferArtistModal').style.display = 'flex';
        }
        
        function hideTransferArtistModal() {
            document.getElementById('transferArtistModal').style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const adminModal = document.getElementById('transferAdminModal');
            const gestionnaireModal = document.getElementById('transferGestionnaireModal');
            const agentModal = document.getElementById('transferAgentModal');
            const artistModal = document.getElementById('transferArtistModal');
            
            if (event.target == adminModal) {
                hideTransferAdminModal();
            }
            if (event.target == gestionnaireModal) {
                hideTransferGestionnaireModal();
            }
            if (event.target == agentModal) {
                hideTransferAgentModal();
            }
            if (event.target == artistModal) {
                hideTransferArtistModal();
            }
        }
    </script>
</x-allthepages-layout>

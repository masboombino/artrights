<x-allthepages-layout pageTitle="Wilaya Details - {{ $wilayaName }}">
    <div class="space-y-6" style="padding: 2rem;">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-bold mb-2" style="color: #D6BFBF;">
                {{ $wilayaCode }} - {{ $wilayaName }}
            </h1>
            <a href="{{ route('superadmin.all-wilayas') }}" 
               class="inline-block rounded transition hover:opacity-90" 
               style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem; margin-top: 1rem;">
                <span>Back to All Wilayas</span>
            </a>
        </div>

        <!-- Statistics -->
        <div class="rounded-lg shadow-lg p-6 mb-6 text-center" style="background-color: #F3EBDD; border: 3px solid #193948;">
            <h2 class="text-2xl font-bold mb-6" style="color: #D6BFBF;">Wilaya Statistics</h2>
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                <div class="text-center">
                    <p class="text-4xl font-bold mb-2" style="color: #193948;">{{ $stats['agencies_count'] }}</p>
                    <p class="text-base font-semibold" style="color: #D6BFBF;">Agencies</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold mb-2" style="color: #193948;">{{ $stats['admins_count'] }}</p>
                    <p class="text-base font-semibold" style="color: #D6BFBF;">Admins</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold mb-2" style="color: #193948;">{{ $stats['gestionnaires_count'] }}</p>
                    <p class="text-base font-semibold" style="color: #D6BFBF;">Gestionnaires</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold mb-2" style="color: #193948;">{{ $stats['agents_count'] }}</p>
                    <p class="text-base font-semibold" style="color: #D6BFBF;">Agents</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold mb-2" style="color: #193948;">{{ $stats['artists_count'] }}</p>
                    <p class="text-base font-semibold" style="color: #D6BFBF;">Artists</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold mb-2" style="color: #193948;">{{ number_format($stats['total_wallet_balance'], 2) }}</p>
                    <p class="text-base font-semibold" style="color: #D6BFBF;">DZD (Total Wallet)</p>
                </div>
            </div>
        </div>

        <!-- Agencies -->
        <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD; margin-bottom: 2rem;">
            <div class="p-4 flex justify-between items-center" style="background-color: #193948;">
                <h3 class="text-xl font-bold" style="color: #D6BFBF;">Agencies ({{ $agencies->count() }})</h3>
            </div>
            <div class="p-4">
                @forelse($agencies as $agency)
                    <div class="mb-4 pb-4 border-b" style="border-color: #193948;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 200px;">
                                <h4 class="text-lg font-bold mb-2" style="color: #193948;">{{ $agency->agency_name }}</h4>
                                <p class="text-sm mb-2" style="color: #193948;">
                                    <strong>Wallet:</strong> {{ number_format($agency->wallet->balance ?? 0, 2) }} DZD
                                </p>
                                <p class="text-sm mb-2" style="color: #193948;">
                                    <strong>Admin:</strong> {{ $agency->admin->name ?? 'Not assigned' }}
                                </p>
                                <p class="text-sm mb-2" style="color: #193948;">
                                    <strong>Gestionnaires:</strong> {{ $agency->gestionnaires->count() }} | 
                                    <strong>Agents:</strong> {{ $agency->agents->count() }} | 
                                    <strong>Artists:</strong> {{ $agency->artists->count() }}
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('superadmin.show-agency', $agency->id) }}" 
                                   class="rounded text-sm transition hover:opacity-90" 
                                   style="background-color: #4FADC0; color: #193948; padding: 0.5rem 1rem; display: inline-block;">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-base text-center" style="color: #193948;">No agencies in this wilaya</p>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Admins -->
            <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD;">
                <div class="p-4 text-center" style="background-color: #193948;">
                    <h3 class="text-xl font-bold" style="color: #D6BFBF;">Admins ({{ $admins->count() }})</h3>
                </div>
                <div class="p-4 max-h-64 overflow-y-auto">
                    @forelse($admins as $admin)
                        <div class="mb-4 pb-4 border-b text-center" style="border-color: #193948;">
                            <p class="text-base font-bold mb-1" style="color: #193948;">{{ $admin->name }}</p>
                            <p class="text-sm mb-1" style="color: #193948;">{{ $admin->email }}</p>
                            <p class="text-sm" style="color: #D6BFBF;">{{ $admin->agency->agency_name ?? 'N/A' }}</p>
                        </div>
                    @empty
                        <p class="text-base text-center" style="color: #193948;">No admins</p>
                    @endforelse
                </div>
            </div>

            <!-- Gestionnaires -->
            <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD;">
                <div class="p-4 text-center" style="background-color: #193948;">
                    <h3 class="text-xl font-bold" style="color: #D6BFBF;">Gestionnaires ({{ $gestionnaires->count() }})</h3>
                </div>
                <div class="p-4 max-h-64 overflow-y-auto">
                    @forelse($gestionnaires as $gestionnaire)
                        <div class="mb-4 pb-4 border-b text-center" style="border-color: #193948;">
                            <p class="text-base font-bold mb-1" style="color: #193948;">{{ $gestionnaire->name }}</p>
                            <p class="text-sm mb-1" style="color: #193948;">{{ $gestionnaire->email }}</p>
                            <p class="text-sm" style="color: #D6BFBF;">{{ $gestionnaire->agency->agency_name ?? 'N/A' }}</p>
                        </div>
                    @empty
                        <p class="text-base text-center" style="color: #193948;">No gestionnaires</p>
                    @endforelse
                </div>
            </div>

            <!-- Agents -->
            <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD;">
                <div class="p-4 text-center" style="background-color: #193948;">
                    <h3 class="text-xl font-bold" style="color: #D6BFBF;">Agents ({{ $agents->count() }})</h3>
                </div>
                <div class="p-4 max-h-64 overflow-y-auto">
                    @forelse($agents as $agent)
                        <div class="mb-4 pb-4 border-b text-center" style="border-color: #193948;">
                            <p class="text-base font-bold mb-1" style="color: #193948;">{{ $agent->user->name ?? 'N/A' }}</p>
                            <p class="text-sm mb-1" style="color: #193948;">{{ $agent->user->email ?? 'N/A' }}</p>
                            <p class="text-sm font-semibold" style="color: #D6BFBF;">Badge: {{ $agent->badge_number }}</p>
                            <p class="text-sm" style="color: #193948;">{{ $agent->agency->agency_name ?? 'N/A' }}</p>
                        </div>
                    @empty
                        <p class="text-base text-center" style="color: #193948;">No agents</p>
                    @endforelse
                </div>
            </div>

            <!-- Artists -->
            <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD;">
                <div class="p-4 text-center" style="background-color: #193948;">
                    <h3 class="text-xl font-bold" style="color: #D6BFBF;">Artists ({{ $artists->count() }})</h3>
                </div>
                <div class="p-4 max-h-64 overflow-y-auto">
                    @forelse($artists as $artist)
                        <div class="mb-4 pb-4 border-b text-center" style="border-color: #193948;">
                            <p class="text-base font-bold mb-1" style="color: #193948;">{{ $artist->user->name ?? 'N/A' }}</p>
                            <p class="text-sm mb-1" style="color: #193948;">{{ $artist->stage_name ?? 'N/A' }}</p>
                            <p class="text-sm mb-2" style="color: #193948;">{{ $artist->user->email ?? 'N/A' }}</p>
                            <p class="text-sm" style="color: #193948;">{{ $artist->agency->agency_name ?? 'N/A' }}</p>
                            <span class="px-3 py-1 rounded text-sm inline-block mt-1" style="background-color: #193948; color: #4FADC0;">
                                {{ $artist->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-base text-center" style="color: #193948;">No artists</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-allthepages-layout>


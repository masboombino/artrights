<x-allthepages-layout pageTitle="Agencies">
    <div class="space-y-6">
        @forelse($agencies as $agency)
            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                <h2 class="text-2xl font-semibold mb-4" style="color: #193948;">{{ $agency->agency_name }}</h2>
                <div class="space-y-2">
                    <p class="text-lg" style="color: #193948;"><strong>Wilaya:</strong> {{ $agency->wilaya }}</p>
                    @if($agency->admin)
                        <p class="text-lg" style="color: #193948;"><strong>Admin:</strong> {{ $agency->admin->name }}</p>
                    @endif
                    <p class="text-lg" style="color: #193948;"><strong>Agents Count:</strong> {{ $agency->agents->count() }}</p>
                </div>
            </div>
        @empty
            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                <p class="text-lg" style="color: #193948;">No agencies found</p>
            </div>
        @endforelse
    </div>
</x-allthepages-layout>


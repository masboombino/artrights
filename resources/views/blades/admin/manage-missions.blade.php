<x-allthepages-layout pageTitle="Manage Missions">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="mb-4" style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="text-2xl font-bold" style="color: #D6BFBF;">Manage Missions</h1>
            <a href="{{ route('admin.create-mission') }}" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem; text-decoration: none; font-weight: 600;">
                Assign Mission
            </a>
        </div>

        <div class="page-container" style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Agent</th>
                        <th>Scheduled At</th>
                        <th>Status</th>
                        <th>Gestionnaire</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($missions as $mission)
                        <tr>
                            <td>{{ $mission->title }}</td>
                            <td>{{ $mission->agent->user->name ?? 'Unassigned' }}</td>
                            <td>{{ $mission->scheduled_at?->format('d/m/Y H:i') ?? 'Not scheduled' }}</td>
                            <td><span class="status-badge">{{ $mission->status }}</span></td>
                            <td>{{ $mission->gestionnaire->name ?? 'Not assigned' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No missions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-allthepages-layout>


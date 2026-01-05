<x-allthepages-layout pageTitle="Manage Missions">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div style="margin-bottom: 1rem; padding: 5px;">
            <a href="{{ route('gestionnaire.missions.create') }}" class="primary-button">
                Create Mission
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($missions as $mission)
                        <tr>
                            <td>{{ $mission->title }}</td>
                            <td>{{ $mission->agent->user->name ?? 'Unassigned' }}</td>
                            <td>{{ $mission->scheduled_at?->format('d/m/Y H:i') ?? 'Not scheduled' }}</td>
                            <td><span class="status-badge">{{ $mission->status }}</span></td>
                            <td>
                                <a href="{{ route('gestionnaire.missions.show', $mission) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No missions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($missions->hasPages())
            <div style="margin: 5px; padding: 5px;">
                {{ $missions->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>

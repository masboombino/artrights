<x-allthepages-layout pageTitle="Manage Agents">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div style="margin-bottom: 1rem; padding: 5px;">
            <a href="{{ route('gestionnaire.agents.create') }}" class="primary-button">
                Create Agent
            </a>
        </div>

        <div class="page-container" style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $agent)
                        <tr>
                            <td>{{ $agent->user->name }}</td>
                            <td>{{ $agent->user->email }}</td>
                            <td>{{ $agent->user->phone ?? 'N/A' }}</td>
                            <td><span class="status-badge">{{ $agent->user->status }}</span></td>
                            <td>{{ $agent->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="#" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No agents found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-allthepages-layout>

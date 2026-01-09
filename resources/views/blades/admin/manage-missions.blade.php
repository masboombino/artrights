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

        <!-- Filters -->
        <div class="stat-card" style="padding: 1rem; margin-bottom: 10px;">
            <form method="GET" action="{{ route('admin.manage-missions') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <label style="color: #193948; font-weight: 600; font-size: 0.9rem;">Filter by Status:</label>
                <select name="status" style="padding: 8px 16px; border: 2px solid #193948; color: #193948; background-color: white; font-size: 0.9rem; border-radius: 0.5rem;">
                    <option value="">All Status</option>
                    <option value="ASSIGNED" {{ request('status') === 'ASSIGNED' ? 'selected' : '' }}>Assigned</option>
                    <option value="IN_PROGRESS" {{ request('status') === 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                    <option value="DONE" {{ request('status') === 'DONE' ? 'selected' : '' }}>Done</option>
                    <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="primary-button" style="padding: 8px 20px; font-size: 0.9rem;">
                    Apply Filter
                </button>
                @if(request('status'))
                    <a href="{{ route('admin.manage-missions') }}" class="secondary-button" style="padding: 8px 20px; font-size: 0.9rem; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </form>
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


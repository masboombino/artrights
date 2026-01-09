<x-allthepages-layout pageTitle="My Missions">
    <div style="padding: 5px; margin: 5px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px; padding: 5px;">
            <h1 style="color: #F3EBDD; font-size: 1.75rem; font-weight: 700; margin: 0;">
                My Missions
            </h1>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Filters -->
        <div class="stat-card" style="padding: 1rem; margin-bottom: 10px;">
            <form method="GET" action="{{ route('agent.missions.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
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
                    <a href="{{ route('agent.missions.index') }}" class="secondary-button" style="padding: 8px 20px; font-size: 0.9rem; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if($missions->count() > 0)
            <div style="background-color: #F3EBDD; border-radius: 1rem; margin: 5px; padding: 1.5rem; overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Scheduled At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($missions as $mission)
                            <tr>
                                <td style="font-weight: 600; color: #193948;">{{ $mission->title }}</td>
                                <td style="color: #193948;">{{ Str::limit($mission->description ?? 'No description', 60) }}</td>
                                <td style="color: #193948;">
                                    {{ $mission->scheduled_at ? $mission->scheduled_at->format('d/m/Y H:i') : 'Not scheduled' }}
                                </td>
                                <td>
                                    <span class="status-badge">{{ $mission->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('agent.missions.show', $mission) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="background-color: #F3EBDD; border-radius: 1rem; margin: 5px; padding: 3rem 1.5rem; text-align: center;">
                <p style="color: #193948; font-size: 1.2rem; font-weight: 600; margin: 0 0 1rem 0;">
                    No missions assigned yet
                </p>
                <p style="color: #193948; font-size: 1rem; margin: 0; opacity: 0.8;">
                    You will see your assigned missions here once they are created by your gestionnaire.
                </p>
            </div>
        @endif
    </div>
</x-allthepages-layout>

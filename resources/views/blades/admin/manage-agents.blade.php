<x-allthepages-layout pageTitle="Manage Agents">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="mb-4" style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="text-2xl font-bold" style="color: #D6BFBF;">Manage Agents</h1>
            <a href="{{ route('admin.create-agent') }}" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem; text-decoration: none; font-weight: 600;">
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
                        <th>Badge Number</th>
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
                            <td><span class="status-badge">{{ $agent->badge_number ?? 'N/A' }}</span></td>
                            <td>{{ $agent->created_at->format('Y-m-d') }}</td>
                            <td>
                                <form action="{{ route('admin.remove-agent', $agent->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this agent?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded text-xs transition hover:opacity-90" style="background-color: #E76268; color: white; padding: 0.5rem 1rem;">
                                        Remove
                                    </button>
                                </form>
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


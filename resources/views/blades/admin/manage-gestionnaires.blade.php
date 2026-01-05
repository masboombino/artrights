<x-allthepages-layout pageTitle="Manage Gestionnaires">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="mb-4" style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="text-2xl font-bold" style="color: #D6BFBF;">Manage Gestionnaires</h1>
            <a href="{{ route('admin.create-gestionnaire') }}" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem; text-decoration: none; font-weight: 600;">
                Create Gestionnaire
            </a>
        </div>

        <div class="page-container" style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Agency</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gestionnaires as $gestionnaire)
                        <tr>
                            <td>{{ $gestionnaire->name }}</td>
                            <td>{{ $gestionnaire->email }}</td>
                            <td>{{ $gestionnaire->phone ?? 'N/A' }}</td>
                            <td>{{ $gestionnaire->agency ? $gestionnaire->agency->agency_name . ' - ' . $gestionnaire->agency->wilaya : 'N/A' }}</td>
                            <td>{{ $gestionnaire->created_at->format('Y-m-d') }}</td>
                            <td>
                                <form action="{{ route('admin.remove-gestionnaire', $gestionnaire->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this gestionnaire?');">
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
                            <td colspan="6">No gestionnaires found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-allthepages-layout>

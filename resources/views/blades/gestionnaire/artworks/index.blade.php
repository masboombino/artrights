<x-allthepages-layout pageTitle="Manage Artworks">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="page-container" style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Agency</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($artworks as $artwork)
                        <tr>
                            <td>{{ $artwork->title }}</td>
                            <td>{{ $artwork->artist->user->name ?? 'N/A' }}</td>
                            <td>{{ $artwork->artist->agency ? $artwork->artist->agency->agency_name . ' - ' . $artwork->artist->agency->wilaya : 'N/A' }}</td>
                            <td>{{ $artwork->category->name ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge">{{ $artwork->status }}</span>
                            </td>
                            <td>{{ $artwork->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('gestionnaire.show-artwork', $artwork->id) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No artworks found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-allthepages-layout>

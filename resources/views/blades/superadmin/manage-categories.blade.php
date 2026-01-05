<x-allthepages-layout pageTitle="Manage Categories">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div style="margin-bottom: 1rem; padding: 5px;">
            <a href="{{ route('superadmin.create-category') }}" class="primary-button">
                Create Category
            </a>
        </div>

        <div class="page-container">
            <div style="overflow-x: auto; width: 100%;">
                <table class="data-table" style="min-width: 700px;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Coefficient</th>
                            <th>Artworks Count</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ Str::limit($category->description ?? 'N/A', 50) }}</td>
                                <td>{{ number_format($category->coefficient ?? 0, 2) }}</td>
                                <td>{{ $category->artworks->count() }}</td>
                                <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div style="display: flex; gap: 5px; flex-wrap: wrap; justify-content: center;">
                                        <a href="{{ route('superadmin.edit-category', $category) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                            Edit
                                        </a>
                                        <form action="{{ route('superadmin.delete-category', $category) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="danger-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No categories found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($categories->hasPages())
            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid rgba(25, 57, 72, 0.1);">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>

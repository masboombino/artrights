<x-allthepages-layout pageTitle="Rejected Artworks">
    <div style="padding: 5px; margin: 5px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px; padding: 5px;">
            <h1 style="color: var(--color-secondary-button); font-size: 1.5rem; font-weight: 700;">Rejected Artworks</h1>
            <a href="{{ route('artist.create-artwork') }}" class="primary-button">
                Create New Artwork
            </a>
        </div>

        @if(session('success'))
            <div style="background-color: #d1fae5; border: 2px solid #10b981; padding: 1rem; border-radius: 0.5rem; margin: 5px;">
                <p style="color: #065f46; font-weight: 600;">{{ session('success') }}</p>
            </div>
        @endif

        <div style="background-color: #F3EBDD; border-radius: 1rem; margin: 5px; padding: 5px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #193948;">
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Title</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Category</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Rejection Reason</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Upload Date</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Rejection Date</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($artworks as $artwork)
                        <tr style="border-top: 1px solid rgba(255, 227, 227, 0.1);">
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->title }}</td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->category->name ?? 'N/A' }}</td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">
                                <span style="background-color: #fee2e2; color: #991b1b; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; display: inline-block; max-width: 300px;">
                                    {{ $artwork->rejection_reason ?? 'No reason provided' }}
                                </span>
                            </td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->created_at->format('Y-m-d H:i') }}</td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->updated_at->format('Y-m-d H:i') }}</td>
                            <td style="padding: 1rem; text-align: center;">
                                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                                    <a href="{{ route('artist.show-artwork', $artwork->id) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        View
                                    </a>
                                    <a href="{{ route('artist.edit-artwork', $artwork->id) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        Edit
                                    </a>
                                    <form action="{{ route('artist.delete-artwork', $artwork->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem; background-color: #E76268;" onclick="return confirm('Are you sure you want to delete this artwork?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="color: #193948; padding: 2rem; text-align: center;">No rejected artworks found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-allthepages-layout>

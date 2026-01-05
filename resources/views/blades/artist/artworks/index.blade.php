<x-allthepages-layout pageTitle="My Artworks">
    <style>
        .artworks-container {
            padding: 5px;
            margin: 5px;
        }

        .artworks-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 10px;
            padding: 5px;
        }

        .artworks-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            padding: 5px;
        }

        .artworks-table-container {
            background-color: #F3EBDD;
            border-radius: 1rem;
            margin: 5px;
            padding: 5px;
            overflow-x: auto;
        }

        .artworks-table {
            width: 100%;
            border-collapse: collapse;
        }

        .actions-container {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .artworks-container {
                padding: 3px;
                margin: 3px;
            }

            .artworks-header {
                margin-bottom: 1rem;
                padding: 3px;
                gap: 8px;
            }

            .artworks-filters {
                gap: 8px;
                margin-bottom: 0.75rem;
                padding: 3px;
            }

            .artworks-table-container {
                margin: 3px;
                padding: 3px;
            }

            .primary-button, .secondary-button {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 640px) {
            .artworks-container {
                padding: 2px;
                margin: 2px;
            }

            .artworks-header {
                flex-direction: column;
                align-items: stretch;
                margin-bottom: 1rem;
                padding: 2px;
                gap: 5px;
            }

            .artworks-filters {
                flex-direction: column;
                gap: 5px;
                margin-bottom: 0.5rem;
                padding: 2px;
            }

            .artworks-table-container {
                margin: 2px;
                padding: 2px;
            }

            .primary-button, .secondary-button {
                padding: 0.35rem 0.7rem;
                font-size: 0.85rem;
                width: 100%;
            }

            .artworks-table th,
            .artworks-table td {
                padding: 0.5rem 0.25rem;
                font-size: 0.85rem;
            }

            .actions-container {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>

    <div class="artworks-container">
        <div class="artworks-header">
            <a href="{{ route('artist.create-artwork') }}" class="primary-button">
                Create New Artwork
            </a>
        </div>

        @if(session('success'))
            <div style="background-color: #d1fae5; border: 2px solid #10b981; padding: 1rem; border-radius: 0.5rem; margin: 5px;">
                <p style="color: #065f46; font-weight: 600;">{{ session('success') }}</p>
            </div>
        @endif

        <div class="artworks-filters">
            <button onclick="showAllArtworks()" class="secondary-button">
                All Artworks
            </button>
            <button onclick="showRejectedArtworks()" class="secondary-button" style="background-color: #E76268;">
                Rejected Artworks
            </button>
        </div>

        <div id="allArtworks" class="artworks-table-container">
            <table class="artworks-table">
                <thead>
                    <tr style="background-color: #193948;">
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Title</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Category</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Status</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Created At</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($artworks as $artwork)
                        <tr style="border-top: 1px solid rgba(255, 227, 227, 0.1);">
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->title }}</td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->category->name ?? 'N/A' }}</td>
                            <td style="color: #193948; padding: 1rem; text-align: center;">
                                <span style="background-color: #193948; color: #4FADC0; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600;">
                                    {{ $artwork->status }}
                                </span>
                            </td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->created_at->format('Y-m-d') }}</td>
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
                            <td colspan="5" style="color: #193948; padding: 2rem; text-align: center;">No artworks found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="rejectedArtworks" class="hidden" style="background-color: #F3EBDD; border-radius: 1rem; margin: 5px; padding: 5px;  overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #193948;">
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Title</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Category</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Rejection Reason</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Created At</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rejectedArtworks as $artwork)
                        <tr style="border-top: 1px solid rgba(255, 227, 227, 0.1);">
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->title }}</td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->category->name ?? 'N/A' }}</td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->rejection_reason ?? 'No reason provided' }}</td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->created_at->format('Y-m-d') }}</td>
                            <td style="padding: 1rem; text-align: center;">
                                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
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
                            <td colspan="5" style="color: #193948; padding: 2rem; text-align: center;">No rejected artworks found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showAllArtworks() {
            document.getElementById('allArtworks').classList.remove('hidden');
            document.getElementById('rejectedArtworks').classList.add('hidden');
        }

        function showRejectedArtworks() {
            document.getElementById('allArtworks').classList.add('hidden');
            document.getElementById('rejectedArtworks').classList.remove('hidden');
        }
    </script>
</x-allthepages-layout>

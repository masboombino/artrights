<x-allthepages-layout pageTitle="Artworks Awaiting Payment">
    <div style="padding: 5px; margin: 5px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px; padding: 5px;">
            <h1 style="color: var(--color-secondary-button); font-size: 1.5rem; font-weight: 700;">Artworks Awaiting Payment</h1>
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
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Tax Amount</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Status</th>
                        <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($artworks as $artwork)
                        <tr style="border-top: 1px solid rgba(255, 227, 227, 0.1);">
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->title }}</td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ $artwork->category->name ?? 'N/A' }}</td>
                            <td style="color: #193948; padding: 1rem; text-align: center; font-size: 0.95rem;">{{ number_format($artwork->platform_tax_amount ?? 0, 2) }} DZD</td>
                            <td style="color: #193948; padding: 1rem; text-align: center;">
                                <span style="background-color: #fbbf24; color: white; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600;">
                                    Awaiting Payment
                                </span>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                                    <a href="{{ route('artist.show-artwork', $artwork->id) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="color: #193948; padding: 2rem; text-align: center;">No artworks awaiting payment</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-allthepages-layout>


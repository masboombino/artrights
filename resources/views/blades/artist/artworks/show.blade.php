@php
use Illuminate\Support\Facades\Storage;

$fileExtension = null;
$isImage = false;
$isVideo = false;
$isAudio = false;
$fileUrl = null;

if ($artwork->file_path) {
    $fileExtension = strtolower(pathinfo($artwork->file_path, PATHINFO_EXTENSION));
    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    $isVideo = in_array($fileExtension, ['mp4', 'webm', 'ogg']);
    $isAudio = in_array($fileExtension, ['mp3', 'wav', 'ogg']);
    $normalizedPath = ltrim($artwork->file_path, '/');
    $fileUrl = route('media.show', ['path' => $normalizedPath]);
}
@endphp

<x-allthepages-layout pageTitle="Artwork Details">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div style="background-color: #d1fae5; border: 2px solid #10b981; padding: 1rem; border-radius: 0.5rem; margin: 5px;">
                <p style="color: #065f46; font-weight: 600;">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #fee2e2; border: 2px solid #E76268; padding: 1rem; border-radius: 0.5rem; margin: 5px;">
                <p style="color: #991b1b; font-weight: 600;">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Action Buttons Section -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin: 5px; margin-bottom: 1rem; flex-wrap: wrap;">
            <a href="{{ route('artist.artworks.live') }}" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #10b981; color: white; padding: 0.75rem 2rem; font-weight: 600; font-size: 1rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: white;">
                    <path d="M9 11L12 14L22 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21 12V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to Live Artworks
            </a>
            <a href="{{ route('artist.artworks') }}" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 2rem; font-weight: 600; font-size: 1rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #193948;">
                    <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to All Artworks
            </a>
            <a href="{{ route('artist.edit-artwork', $artwork->id) }}" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #4FADC0; color: white; padding: 0.75rem 2rem; font-weight: 600; font-size: 1rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: white;">
                    <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18.5 2.50023C18.8978 2.10243 19.4374 1.87891 20 1.87891C20.5626 1.87891 21.1022 2.10243 21.5 2.50023C21.8978 2.89804 22.1213 3.43762 22.1213 4.00023C22.1213 4.56284 21.8978 5.10243 21.5 5.50023L12 15.0002L8 16.0002L9 12.0002L18.5 2.50023Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('artist.delete-artwork', $artwork->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete();">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #E76268; color: white; padding: 0.75rem 2rem; font-weight: 600; font-size: 1rem; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: white;">
                        <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>

        <script>
            function confirmDelete() {
                return confirm('Are you sure you want to delete this artwork? This action cannot be undone.');
            }
        </script>

        <!-- Artwork Display Section -->
        <div style="background-color: #F3EBDD; border: 3px solid #193948; border-radius: 20px; overflow: hidden; margin: 5px;">
            <div style="padding: 2rem;">
                @if($artwork->file_path)
                    @if($isImage)
                        <div style="display: flex; justify-content: center; align-items: center; min-height: 400px; background-color: #ffffff; border-radius: 12px; padding: 20px;">
                            <button type="button" onclick="openArtworkLightbox('{{ $fileUrl }}')" style="border: none; background: transparent; cursor: pointer; padding: 0;">
                                <img src="{{ $fileUrl }}" alt="{{ $artwork->title }}" style="max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                            </button>
                        </div>
                        
                        <style>
                            .artwork-lightbox {
                                display: none;
                                position: fixed;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                background: rgba(0, 0, 0, 0.95);
                                z-index: 10000;
                                justify-content: center;
                                align-items: center;
                            }

                            .artwork-lightbox img {
                                max-width: 90%;
                                max-height: 90%;
                                object-fit: contain;
                                transition: transform 0.3s ease;
                            }

                            .lightbox-close-btn-artwork {
                                position: absolute;
                                top: 20px;
                                right: 30px;
                                font-size: 40px;
                                font-weight: bold;
                                color: white;
                                background: transparent;
                                border: none;
                                cursor: pointer;
                                z-index: 10001;
                                line-height: 1;
                                padding: 0;
                                width: 40px;
                                height: 40px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            }

                            .lightbox-close-btn-artwork:hover {
                                color: #D6BFBF;
                            }

                            .lightbox-rotate-btn-artwork {
                                position: absolute;
                                top: 50%;
                                transform: translateY(-50%);
                                font-size: 40px;
                                font-weight: bold;
                                color: white;
                                background: rgba(0, 0, 0, 0.5);
                                border: none;
                                cursor: pointer;
                                z-index: 10001;
                                padding: 20px 15px;
                                border-radius: 5px;
                                transition: background 0.3s;
                            }

                            .lightbox-rotate-btn-artwork:hover {
                                background: rgba(0, 0, 0, 0.8);
                            }

                            .lightbox-rotate-left-artwork {
                                left: 20px;
                            }

                            .lightbox-rotate-right-artwork {
                                right: 20px;
                            }
                        </style>
                        
                        <div id="artwork-lightbox-{{ $artwork->id }}" class="artwork-lightbox">
                            <button type="button" class="lightbox-close-btn-artwork" onclick="closeArtworkLightbox('{{ $artwork->id }}')">&times;</button>
                            <button type="button" class="lightbox-rotate-btn-artwork lightbox-rotate-left-artwork" onclick="rotateArtworkImage('{{ $artwork->id }}', -90)">↺</button>
                            <img id="artwork-lightbox-img-{{ $artwork->id }}" src="" alt="{{ $artwork->title }} Preview" style="transform: rotate(0deg);">
                            <button type="button" class="lightbox-rotate-btn-artwork lightbox-rotate-right-artwork" onclick="rotateArtworkImage('{{ $artwork->id }}', 90)">↻</button>
                        </div>
                        
                        <script>
                            window.artworkRotation = window.artworkRotation || {};
                            
                            function openArtworkLightbox(imageUrl) {
                                const lightboxId = 'artwork-lightbox-{{ $artwork->id }}';
                                const lightbox = document.getElementById(lightboxId);
                                const img = document.getElementById('artwork-lightbox-img-{{ $artwork->id }}');
                                
                                if (lightbox && img) {
                                    img.src = imageUrl;
                                    img.style.transform = 'rotate(0deg)';
                                    window.artworkRotation['{{ $artwork->id }}'] = 0;
                                    lightbox.style.display = 'flex';
                                }
                            }
                            
                            function closeArtworkLightbox(artworkId) {
                                const lightbox = document.getElementById('artwork-lightbox-' + artworkId);
                                if (lightbox) {
                                    lightbox.style.display = 'none';
                                }
                            }
                            
                            function rotateArtworkImage(artworkId, degrees) {
                                if (!window.artworkRotation[artworkId]) {
                                    window.artworkRotation[artworkId] = 0;
                                }
                                window.artworkRotation[artworkId] += degrees;
                                
                                const img = document.getElementById('artwork-lightbox-img-' + artworkId);
                                if (img) {
                                    img.style.transform = 'rotate(' + window.artworkRotation[artworkId] + 'deg)';
                                }
                            }
                            
                            document.addEventListener('keydown', function(e) {
                                if (e.key === 'Escape') {
                                    document.querySelectorAll('.artwork-lightbox').forEach(lightbox => {
                                        if (lightbox.style.display === 'flex') {
                                            lightbox.style.display = 'none';
                                        }
                                    });
                                }
                            });
                        </script>
                    @elseif($isVideo)
                        <div style="display: flex; justify-content: center; align-items: center; min-height: 400px; background-color: #000000; border-radius: 12px; padding: 20px;">
                            <video controls style="max-width: 100%; max-height: 70vh; border-radius: 8px;">
                                <source src="{{ $fileUrl }}" type="video/{{ $fileExtension }}">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @elseif($isAudio)
                        <div style="display: flex; justify-content: center; align-items: center; min-height: 200px; background-color: #ffffff; border-radius: 12px; padding: 40px;">
                            <audio controls style="width: 100%; max-width: 800px;">
                                <source src="{{ $fileUrl }}" type="audio/{{ $fileExtension }}">
                                Your browser does not support the audio tag.
                            </audio>
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 300px; background-color: #ffffff; border-radius: 12px; padding: 40px;">
                            <div style="text-align: center; margin-bottom: 1rem;">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #193948;">
                                    <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.89 22 5.99 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #193948;">File: {{ strtoupper($fileExtension) }} Document</p>
                            <a href="{{ $fileUrl }}" target="_blank" class="secondary-button">
                                View/Download File
                            </a>
                        </div>
                    @endif
                @else
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 300px; background-color: #ffffff; border-radius: 12px; padding: 40px; text-align: center;">
                        <div style="margin-bottom: 1rem;">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #193948;">
                                <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.89 22 5.99 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: #193948;">No File Uploaded</p>
                        <p style="font-size: 0.95rem; color: #193948; opacity: 0.8;">This artwork does not have an associated file.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Artwork Information Section -->
        <div style="background-color: #F3EBDD; border: 3px solid #193948; border-radius: 20px; padding: 2rem; margin: 5px;">
            <div style="margin-bottom: 1.5rem;">
                <h2 style="color: #193948; font-size: 2rem; font-weight: 700; margin-bottom: 0.75rem;">
                    {{ $artwork->title }}
                </h2>
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                    <span style="background-color: #193948; color: #4FADC0; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600;">
                        {{ $artwork->status }}
                    </span>
                    @if($artwork->platform_tax_status)
                        <span style="background-color: {{ $artwork->platform_tax_status === 'PAID' ? '#10b981' : '#E76268' }}; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600;">
                            Tax: {{ $artwork->platform_tax_status }}
                        </span>
                    @endif
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <p style="color: #193948; font-size: 0.9rem; font-weight: 700; margin-bottom: 0.5rem;">Category:</p>
                    <p style="color: #193948; font-size: 0.95rem; padding: 0.5rem; background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">{{ $artwork->category->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p style="color: #193948; font-size: 0.9rem; font-weight: 700; margin-bottom: 0.5rem;">Created At:</p>
                    <p style="color: #193948; font-size: 0.95rem; padding: 0.5rem; background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">{{ $artwork->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <p style="color: #193948; font-size: 0.9rem; font-weight: 700; margin-bottom: 0.5rem;">Description:</p>
                <div style="padding: 1rem; background-color: #ffffff; border: 2px solid #193948; border-radius: 12px;">
                    <p style="color: #36454f; font-size: 0.95rem; line-height: 1.8; white-space: pre-wrap;">{{ $artwork->description ?? 'No description' }}</p>
                </div>
            </div>

            @if($artwork->rejection_reason)
                <div style="margin-bottom: 1.5rem;">
                    <p style="color: #193948; font-size: 0.9rem; font-weight: 700; margin-bottom: 0.5rem;">Rejection Reason:</p>
                    <div style="padding: 1rem; background-color: #fee2e2; border: 2px solid #E76268; border-radius: 12px;">
                        <p style="color: #991b1b; font-size: 0.95rem; line-height: 1.8; white-space: pre-wrap;">{{ $artwork->rejection_reason }}</p>
                    </div>
                </div>
            @endif
        </div>

        @if($artwork->status === 'APPROVED' && $artwork->platform_tax_status === 'PENDING')
            <div style="background-color: #F3EBDD; border: 4px solid #E76268; border-radius: 1rem; padding: 2rem; margin: 5px; ">
                <h3 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">
                    ⚠️ Platform Tax Payment Required
                </h3>
                <p style="color: #193948; font-size: 1rem; margin-bottom: 1rem;">
                    Your artwork has been approved! However, you must pay a platform tax of 
                    <strong>{{ number_format($artwork->platform_tax_amount, 2) }} DZD</strong> 
                    to activate it and make it available for use in PVs.
                </p>
                <p style="color: #193948; font-size: 0.95rem; margin-bottom: 1rem;">
                    Current Wallet Balance: <strong>{{ number_format($wallet->balance, 2) }} DZD</strong>
                </p>
                
                @if($wallet->balance >= $artwork->platform_tax_amount)
                    <form action="{{ route('artist.pay-platform-tax', $artwork->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="primary-button" style="background-color: #10b981;">
                            Pay {{ number_format($artwork->platform_tax_amount, 2) }} DZD from Wallet
                        </button>
                    </form>
                @else
                    <p style="color: #E76268; font-size: 0.9rem; margin-bottom: 1rem;">
                        Insufficient balance. Please recharge your wallet first.
                    </p>
                    <a href="{{ route('artist.wallet') }}" class="secondary-button">
                        Go to Wallet
                    </a>
                @endif
            </div>
        @endif

        @if($artwork->platform_tax_status === 'PAID')
            <div style="background-color: #10b981; color: white; border-radius: 1rem; padding: 2rem; margin: 5px;">
                <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">✅ Platform Tax Paid</h3>
                <p style="font-size: 0.95rem;">
                    Your artwork is now active and available for use in PVs!
                    @if($artwork->platform_tax_paid_at)
                        Paid on: {{ $artwork->platform_tax_paid_at->format('Y-m-d H:i') }}
                    @endif
                </p>
            </div>
        @endif

    </div>
</x-allthepages-layout>

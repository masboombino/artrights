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

<x-allthepages-layout pageTitle="View Artwork">
    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 rounded mb-4" style="background-color: #F3EBDD; color: #193948;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Artwork Display Section -->
        @if($artwork->file_path)
            @once
                <style>
                    .identity-lightbox {
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
                    .identity-lightbox img {
                        max-width: 90%;
                        max-height: 90%;
                        object-fit: contain;
                        transition: transform 0.3s ease;
                    }
                    .lightbox-close-btn {
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
                    .lightbox-close-btn:hover {
                        color: #D6BFBF;
                    }
                    .lightbox-rotate-btn {
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
                    .lightbox-rotate-btn:hover {
                        background: rgba(0, 0, 0, 0.8);
                    }
                    .lightbox-rotate-left {
                        left: 20px;
                    }
                    .lightbox-rotate-right {
                        right: 20px;
                    }
                    @media (max-width: 768px) {
                        .lightbox-close-btn {
                            top: 10px;
                            right: 15px;
                            font-size: 30px;
                        }
                        .lightbox-rotate-btn {
                            font-size: 30px;
                            padding: 15px 10px;
                        }
                        .lightbox-rotate-left {
                            left: 10px;
                        }
                        .lightbox-rotate-right {
                            right: 10px;
                        }
                    }
                </style>
            @endonce
            <div class="rounded-lg shadow-lg overflow-hidden" style="background-color: #F3EBDD; border: 3px solid #193948;">
                <div class="p-6">
                    @if($isImage)
                        <div class="flex justify-center items-center" style="min-height: 400px; background-color: #ffffff; border-radius: 12px; padding: 20px;">
                            <button type="button" onclick="openArtworkLightbox('{{ $fileUrl }}')" style="border: none; background: transparent; cursor: pointer; padding: 0;">
                                <img src="{{ $fileUrl }}" alt="{{ $artwork->title }}" class="max-w-full max-h-[70vh] object-contain" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                            </button>
                        </div>
                        
                        <div id="artwork-lightbox-{{ $artwork->id }}" class="identity-lightbox">
                            <button type="button" class="lightbox-close-btn" onclick="closeArtworkLightbox('{{ $artwork->id }}')">&times;</button>
                            <button type="button" class="lightbox-rotate-btn lightbox-rotate-left" onclick="rotateArtworkImage('{{ $artwork->id }}', -90)">↺</button>
                            <img id="artwork-lightbox-img-{{ $artwork->id }}" src="" alt="{{ $artwork->title }} Preview" style="transform: rotate(0deg);">
                            <button type="button" class="lightbox-rotate-btn lightbox-rotate-right" onclick="rotateArtworkImage('{{ $artwork->id }}', 90)">↻</button>
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
                                    document.querySelectorAll('.identity-lightbox').forEach(lightbox => {
                                        if (lightbox.style.display === 'flex') {
                                            lightbox.style.display = 'none';
                                        }
                                    });
                                }
                            });
                        </script>
                    @elseif($isVideo)
                        <div class="flex justify-center items-center" style="min-height: 400px; background-color: #000000; border-radius: 12px; padding: 20px;">
                            <video controls class="max-w-full max-h-[70vh]" style="border-radius: 8px;">
                                <source src="{{ $fileUrl }}" type="video/{{ $fileExtension }}">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @elseif($isAudio)
                        <div class="flex justify-center items-center" style="min-height: 200px; background-color: #ffffff; border-radius: 12px; padding: 40px;">
                            <audio controls class="w-full max-w-2xl">
                                <source src="{{ $fileUrl }}" type="audio/{{ $fileExtension }}">
                                Your browser does not support the audio tag.
                            </audio>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center" style="min-height: 300px; background-color: #ffffff; border-radius: 12px; padding: 40px;">
                            <div class="text-center mb-4">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #193948;">
                                    <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.89 22 5.99 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <p class="text-lg font-semibold mb-4" style="color: #193948;">File: {{ strtoupper($fileExtension) }} Document</p>
                            <a href="{{ route('gestionnaire.download-artwork', $artwork->id) }}" target="_blank" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem; font-weight: 600;">
                                View/Download File
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Artwork Information Section -->
        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948; border-radius: 20px;">
            <div class="mb-6">
                <h2 class="text-3xl font-bold mb-3" style="color: #193948;">{{ $artwork->title }}</h2>
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="px-4 py-2 rounded-lg text-sm font-bold" style="background-color: #193948; color: #4FADC0;">
                        {{ $artwork->status }}
                    </span>
                    @if($artwork->platform_tax_status)
                        <span class="px-4 py-2 rounded-lg text-sm font-bold" style="background-color: {{ $artwork->platform_tax_status === 'PAID' ? '#10b981' : '#E76268' }}; color: white;">
                            Tax: {{ $artwork->platform_tax_status }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold mb-1" style="color: #193948;">Artist</label>
                    <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artwork->artist->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1" style="color: #193948;">Agency</label>
                    <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artwork->artist->agency ? $artwork->artist->agency->agency_name . ' - ' . $artwork->artist->agency->wilaya : 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1" style="color: #193948;">Category</label>
                    <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artwork->category->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1" style="color: #193948;">Created At</label>
                    <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artwork->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1" style="color: #193948;">Updated At</label>
                    <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artwork->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold mb-2" style="color: #193948;">Description</label>
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 12px;">
                    <p class="text-base leading-relaxed whitespace-pre-wrap" style="color: #36454f; line-height: 1.8;">{{ $artwork->description ?? 'No description' }}</p>
                </div>
            </div>

            @if($artwork->rejection_reason)
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2" style="color: #193948;">Rejection Reason</label>
                    <div class="p-4 rounded" style="background-color: #fee2e2; border: 2px solid #E76268; border-radius: 12px;">
                        <p class="text-base leading-relaxed whitespace-pre-wrap" style="color: #991b1b; line-height: 1.8;">{{ $artwork->rejection_reason }}</p>
                    </div>
                </div>
            @endif
        </div>

        @if($artwork->status === 'PENDING')
            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                <h3 class="text-xl font-semibold mb-4" style="color: #193948;">Actions</h3>
                <div class="flex gap-4">
                    <form action="{{ route('gestionnaire.approve-artwork', $artwork->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-block rounded text-sm transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 0.75rem 1.5rem;">
                            <span style="padding: 0 0.25rem;">Approve</span>
                        </button>
                    </form>
                    <button type="button" onclick="showRejectForm()" class="inline-block rounded text-sm transition hover:opacity-90" style="background-color: #dc2626; color: white; padding: 0.75rem 1.5rem;">
                        <span style="padding: 0 0.25rem;">Reject</span>
                    </button>
                </div>

                <div id="rejectForm" class="hidden mt-4">
                    <form action="{{ route('gestionnaire.reject-artwork', $artwork->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="rejection_reason" class="block text-sm font-semibold mb-2" style="color: #193948;">Rejection Reason:</label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="4" required class="w-full p-2 rounded" style="border: 2px solid #193948; color: #193948;">{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                                <p class="text-sm mt-1" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" class="inline-block rounded text-sm transition hover:opacity-90" style="background-color: #dc2626; color: white; padding: 0.75rem 1.5rem;">
                                <span style="padding: 0 0.25rem;">Confirm Reject</span>
                            </button>
                            <button type="button" onclick="hideRejectForm()" class="inline-block rounded text-sm transition hover:opacity-90" style="background-color: #6b7280; color: white; padding: 0.75rem 1.5rem;">
                                <span style="padding: 0 0.25rem;">Cancel</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="flex justify-center">
            <a href="{{ route('gestionnaire.artworks') }}" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 2rem; font-weight: 600; font-size: 1rem;">
                Back to Artworks
            </a>
        </div>
    </div>

    <script>
        function showRejectForm() {
            document.getElementById('rejectForm').classList.remove('hidden');
        }

        function hideRejectForm() {
            document.getElementById('rejectForm').classList.add('hidden');
        }
    </script>
</x-allthepages-layout>


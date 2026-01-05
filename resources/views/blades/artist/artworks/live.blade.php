<x-allthepages-layout pageTitle="Live Artworks">
    <style>
        .artworks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        /* Responsive Styles for Mobile */
        @media (max-width: 768px) {
            .artworks-grid {
                grid-template-columns: 1fr !important;
                gap: 1rem;
                padding: 0.75rem;
            }

            .artwork-card {
                max-width: 100% !important;
                width: 100% !important;
                min-width: 0;
            }

            .artwork-image,
            .artwork-video,
            .artwork-audio-container,
            .artwork-file-container {
                height: 200px;
                max-width: 100%;
            }

            .artwork-content {
                padding: 0.75rem 1rem;
                max-width: 100%;
            }

            .artwork-title {
                font-size: 1.1rem;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            .artwork-description {
                font-size: 0.9rem;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            h1 {
                font-size: 1.25rem !important;
            }

            .primary-button {
                padding: 0.4rem 1rem !important;
                font-size: 0.9rem !important;
            }
        }

        @media (max-width: 640px) {
            .artworks-grid {
                grid-template-columns: 1fr !important;
                gap: 0.75rem;
                padding: 0.5rem;
            }

            .artwork-card {
                max-width: 100% !important;
                width: 100% !important;
            }

            .artwork-image,
            .artwork-video,
            .artwork-audio-container,
            .artwork-file-container {
                height: 180px;
                max-width: 100%;
            }

            .artwork-content {
                padding: 0.5rem 0.75rem;
                max-width: 100%;
            }

            .artwork-button .secondary-button {
                padding: 0.6rem 0.75rem;
                font-size: 0.9rem;
            }

            h1 {
                font-size: 1.1rem !important;
            }

            .primary-button {
                padding: 0.35rem 0.8rem !important;
                font-size: 0.85rem !important;
            }
        }

        @media (max-width: 480px) {
            .artworks-grid {
                grid-template-columns: 1fr !important;
                gap: 0.5rem;
                padding: 0.25rem;
            }

            .artwork-card {
                max-width: 100% !important;
                width: 100% !important;
                min-width: 0;
            }

            .artwork-image,
            .artwork-video,
            .artwork-audio-container,
            .artwork-file-container {
                height: 160px;
                max-width: 100%;
            }

            .artwork-content {
                padding: 0.5rem;
                max-width: 100%;
            }

            .artwork-title {
                font-size: 1rem;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            .artwork-description {
                font-size: 0.85rem;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            .artwork-button .secondary-button {
                padding: 0.5rem;
                font-size: 0.85rem;
            }

            h1 {
                font-size: 1rem !important;
            }

            .primary-button {
                padding: 0.3rem 0.7rem !important;
                font-size: 0.8rem !important;
                width: 100%;
                text-align: center;
            }
        }

        .artwork-card {
            background-color: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .artwork-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .artwork-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background-color: #f3f4f6;
        }

        .artwork-content {
            padding: 1rem 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .artwork-category {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .artwork-title {
            color: #193948;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .artwork-description {
            color: #4b5563;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            line-height: 1.5;
            flex: 1;
        }

        .artwork-button {
            margin: 0 -1.5rem -1rem -1.5rem;
            margin-top: auto;
        }

        .artwork-button .secondary-button {
            width: 100%;
            text-align: center;
            display: block;
            border-radius: 0;
            margin: 0;
            padding: 0.75rem 1rem;
        }

        /* Video Preview */
        .artwork-video {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background-color: #1a1a1a;
        }

        /* Audio Preview */
        .artwork-audio-container {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #193948 0%, #2d5a6b 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .audio-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .audio-title {
            color: white;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 1rem;
            max-width: 90%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .artwork-audio {
            width: 90%;
            height: 40px;
        }

        /* Document/Other File Preview */
        .artwork-file-container {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #4b5563 0%, #6b7280 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .file-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .file-type {
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>

    <div style="padding: 5px; margin: 5px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px; padding: 5px;">
            <h1 style="color: var(--color-secondary-button); font-size: 1.5rem; font-weight: 700;">Your Live Artworks</h1>
            <a href="{{ route('artist.create-artwork') }}" class="primary-button">
                Create New Artwork
            </a>
        </div>

        @if(session('success'))
            <div style="background-color: #d1fae5; border: 2px solid #10b981; padding: 1rem; border-radius: 0.5rem; margin: 5px;">
                <p style="color: #065f46; font-weight: 600;">{{ session('success') }}</p>
            </div>
        @endif

        <div style="background-color: #F3EBDD; border-radius: 1rem; padding: 1rem; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;">
            @if($artworks->count() > 0)
                <div class="artworks-grid">
                    @foreach($artworks as $artwork)
                        @php
                            $fileUrl = null;
                            $fileType = 'none';
                            $fileExtension = '';
                            if ($artwork->file_path) {
                                $fileExtension = strtolower(pathinfo($artwork->file_path, PATHINFO_EXTENSION));
                                $normalizedPath = ltrim($artwork->file_path, '/');
                                $fileUrl = route('media.show', ['path' => $normalizedPath]);
                                
                                // Determine file type
                                if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'])) {
                                    $fileType = 'image';
                                } elseif (in_array($fileExtension, ['mp4', 'webm', 'mov', 'avi', 'mkv', 'wmv'])) {
                                    $fileType = 'video';
                                } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg', 'flac', 'aac', 'm4a'])) {
                                    $fileType = 'audio';
                                } elseif (in_array($fileExtension, ['pdf'])) {
                                    $fileType = 'pdf';
                                } else {
                                    $fileType = 'other';
                                }
                            }
                        @endphp
                        <div class="artwork-card">
                            @if($fileType === 'image')
                                <img src="{{ $fileUrl }}" alt="{{ $artwork->title }}" class="artwork-image">
                            @elseif($fileType === 'video')
                                <video class="artwork-video" preload="metadata" muted>
                                    <source src="{{ $fileUrl }}" type="video/{{ $fileExtension === 'mov' ? 'quicktime' : $fileExtension }}">
                                </video>
                            @elseif($fileType === 'audio')
                                <div class="artwork-audio-container">
                                    <div class="audio-icon">🎵</div>
                                    <div class="audio-title">{{ $artwork->title }}</div>
                                    <audio class="artwork-audio" controls preload="metadata">
                                        <source src="{{ $fileUrl }}" type="audio/{{ $fileExtension === 'm4a' ? 'mp4' : $fileExtension }}">
                                    </audio>
                                </div>
                            @elseif($fileType === 'pdf')
                                <div class="artwork-file-container" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);">
                                    <div class="file-icon">📄</div>
                                    <div class="file-type">PDF Document</div>
                                </div>
                            @elseif($fileType === 'other' && $fileUrl)
                                <div class="artwork-file-container">
                                    <div class="file-icon">📁</div>
                                    <div class="file-type">{{ strtoupper($fileExtension) }} File</div>
                                </div>
                            @else
                                <div class="artwork-image" style="display: flex; align-items: center; justify-content: center; color: #9ca3af; background-color: #f3f4f6;">
                                    <span>No File</span>
                                </div>
                            @endif
                            <div class="artwork-content">
                                <div class="artwork-category">{{ $artwork->category->name ?? 'Unspecified' }}</div>
                                <h2 class="artwork-title">{{ $artwork->title }}</h2>
                                @if($artwork->description)
                                    <p class="artwork-description">{{ Str::limit($artwork->description, 100) }}</p>
                                @endif
                                <div class="artwork-button">
                                    <a href="{{ route('artist.show-artwork', $artwork->id) }}" class="secondary-button">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #193948;">
                    <p style="font-size: 1.25rem; margin-bottom: 1rem;">No live artworks currently</p>
                    <a href="{{ route('artist.create-artwork') }}" class="primary-button">
                        Create New Artwork
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-allthepages-layout>

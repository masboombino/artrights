@if(!empty($images) && count($images) > 0)
    @once
        <style>
            .complaint-gallery {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
                margin: 1rem 0;
            }

            .complaint-thumb {
                position: relative;
                padding: 0;
                border: 2px solid #193948;
                border-radius: 8px;
                overflow: hidden;
                cursor: pointer;
                background: transparent;
                transition: transform 0.2s, box-shadow 0.2s;
                aspect-ratio: 1;
                -webkit-tap-highlight-color: transparent;
                outline: none;
            }

            .complaint-thumb:hover {
                transform: translateY(-4px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }

            .complaint-thumb img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                pointer-events: none;
                user-select: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
            }

            .complaint-lightbox {
                display: none !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                min-width: 100vw !important;
                min-height: 100vh !important;
                max-width: 100vw !important;
                max-height: 100vh !important;
                background: rgba(0, 0, 0, 0.95) !important;
                z-index: 999999 !important;
                justify-content: center !important;
                align-items: center !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                overflow: hidden !important;
                box-sizing: border-box !important;
                inset: 0 !important;
            }

            .complaint-lightbox[style*="display: flex"],
            .complaint-lightbox[style*="display:flex"],
            .complaint-lightbox.show {
                display: flex !important;
            }
            
            /* Ensure lightbox is always on top */
            body.lightbox-open {
                overflow: hidden !important;
            }
            
            body.lightbox-open * {
                pointer-events: none;
            }
            
            body.lightbox-open .complaint-lightbox,
            body.lightbox-open .complaint-lightbox * {
                pointer-events: auto !important;
            }

            .complaint-lightbox img {
                max-width: 95vw !important;
                max-height: 95vh !important;
                min-width: auto !important;
                min-height: auto !important;
                width: auto !important;
                height: auto !important;
                object-fit: contain !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                display: block !important;
                position: relative !important;
                flex-shrink: 0 !important;
                image-rendering: auto !important;
            }

            .lightbox-close {
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

            .lightbox-close:hover {
                color: #D6BFBF;
            }

            .lightbox-arrow {
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

            .lightbox-arrow:hover {
                background: rgba(0, 0, 0, 0.8);
            }

            .lightbox-arrow-left {
                left: 20px;
            }

            .lightbox-arrow-right {
                right: 20px;
            }

            @media (max-width: 768px) {
                .complaint-gallery {
                    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                    gap: 0.5rem;
                }

                .lightbox-close {
                    top: 10px;
                    right: 15px;
                    font-size: 30px;
                }

                .lightbox-arrow {
                    font-size: 30px;
                    padding: 15px 10px;
                }

                .lightbox-arrow-left {
                    left: 10px;
                }

                .lightbox-arrow-right {
                    right: 10px;
                }
            }
        </style>
    @endonce

    @php
        $useStorageRoute = $useStorageRoute ?? true; // Default to route() for media.show
        $galleryImages = collect($images)->map(function ($image) use ($useStorageRoute) {
            if (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])) {
                return $image;
            }

            $normalizedPath = ltrim($image, '/');

            return $useStorageRoute
                ? route('media.show', ['path' => $normalizedPath])
                : asset('storage/' . $normalizedPath);
        })->values()->toArray();
    @endphp

    <div class="complaint-gallery">
        @foreach($galleryImages as $index => $imageUrl)
            <button type="button" class="complaint-thumb" data-gallery-id="{{ $galleryId }}" data-image-index="{{ $index }}" tabindex="0">
                <img src="{{ $imageUrl }}" alt="Image {{ $index + 1 }}" draggable="false" oncontextmenu="return false;" loading="lazy">
            </button>
        @endforeach
    </div>

    <div id="lightbox-{{ $galleryId }}" class="complaint-lightbox">
        <button type="button" class="lightbox-close" onclick="closeLightbox('{{ $galleryId }}')">&times;</button>
        <button type="button" class="lightbox-arrow lightbox-arrow-left" onclick="changeImage('{{ $galleryId }}', -1)">&#8592;</button>
        <img id="lightbox-img-{{ $galleryId }}" src="" alt="Preview">
        <button type="button" class="lightbox-arrow lightbox-arrow-right" onclick="changeImage('{{ $galleryId }}', 1)">&#8594;</button>
    </div>

    @once
        @push('scripts')
            <script>
                window.lightboxData = window.lightboxData || {};

                function registerLightbox(id, images) {
                    window.lightboxData[id] = { images: images || [], index: 0 };
                }

                function openLightbox(id, index, e) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        e.cancelBubble = true;
                        if (e.stopImmediatePropagation) {
                            e.stopImmediatePropagation();
                        }
                    }
                    const data = window.lightboxData[id];
                    const overlay = document.getElementById('lightbox-' + id);
                    if (!data || !overlay || !data.images.length) {
                        return false;
                    }
                    // Prevent any default image opening behavior
                    if (e && e.target && e.target.tagName === 'IMG') {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        e.cancelBubble = true;
                    }
                    data.index = index;
                    updateLightboxImage(id);
                    overlay.style.display = 'flex';
                    overlay.style.zIndex = '999999';
                    overlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                    document.body.classList.add('lightbox-open');
                    // Prevent any other handlers
                    if (e) {
                        setTimeout(function() {
                            if (e.target && e.target.tagName === 'IMG') {
                                e.target.style.pointerEvents = 'none';
                            }
                        }, 0);
                    }
                    return false;
                }

                function closeLightbox(id) {
                    const overlay = document.getElementById('lightbox-' + id);
                    if (overlay) {
                        overlay.style.display = 'none';
                        overlay.classList.remove('show');
                    }
                    document.body.style.overflow = '';
                    document.body.classList.remove('lightbox-open');
                }

                function changeImage(id, direction) {
                    const data = window.lightboxData[id];
                    if (!data || !data.images.length) {
                        return;
                    }
                    data.index += direction;
                    if (data.index < 0) {
                        data.index = data.images.length - 1;
                    } else if (data.index >= data.images.length) {
                        data.index = 0;
                    }
                    updateLightboxImage(id);
                }

                function updateLightboxImage(id) {
                    const data = window.lightboxData[id];
                    if (!data) {
                        return;
                    }
                    const img = document.getElementById('lightbox-img-' + id);
                    if (img) {
                        // Reset image to ensure proper sizing
                        img.style.width = 'auto';
                        img.style.height = 'auto';
                        img.style.maxWidth = '95vw';
                        img.style.maxHeight = '95vh';
                        img.style.objectFit = 'contain';
                        
                        // Load new image
                        img.src = data.images[data.index];
                        
                        // Ensure image displays at full size after load
                        img.onload = function() {
                            this.style.width = 'auto';
                            this.style.height = 'auto';
                            this.style.maxWidth = '95vw';
                            this.style.maxHeight = '95vh';
                            this.style.objectFit = 'contain';
                        };
                    }
                }

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        Object.keys(window.lightboxData).forEach(function(id) {
                            closeLightbox(id);
                        });
                        document.body.style.overflow = '';
                    }
                });
            </script>
        @endpush
    @endonce

    @push('scripts')
        <script>
            registerLightbox('{{ $galleryId }}', @json($galleryImages));
            
            // Prevent default image opening behavior and handle clicks
            (function() {
                const galleryId = '{{ $galleryId }}';
                
                function setupThumbnails() {
                    const thumbs = document.querySelectorAll('.complaint-gallery .complaint-thumb[data-gallery-id="' + galleryId + '"]');
                    
                    thumbs.forEach(function(thumb) {
                        const imageIndex = parseInt(thumb.getAttribute('data-image-index'));
                        
                        // Remove any existing event listeners by cloning
                        const newThumb = thumb.cloneNode(true);
                        thumb.parentNode.replaceChild(newThumb, thumb);
                        
                        // Prevent all default behaviors - use capture phase
                        newThumb.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            e.cancelBubble = true;
                            openLightbox(galleryId, imageIndex, e);
                            return false;
                        }, true); // Use capture phase
                        
                        newThumb.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                        }, true);
                        
                        newThumb.addEventListener('dblclick', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            return false;
                        }, true);
                        
                        newThumb.addEventListener('contextmenu', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            return false;
                        }, true);
                        
                        // Handle keyboard (Enter/Space)
                        newThumb.addEventListener('keydown', function(e) {
                            if (e.key === 'Enter' || e.key === ' ') {
                                e.preventDefault();
                                e.stopPropagation();
                                openLightbox(galleryId, imageIndex, e);
                            }
                        }, false);
                        
                        // Prevent image from opening in new tab/window
                        const img = newThumb.querySelector('img');
                        if (img) {
                            // Remove any existing href or onclick
                            img.removeAttribute('href');
                            img.removeAttribute('onclick');
                            
                            img.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                e.stopImmediatePropagation();
                                e.cancelBubble = true;
                            }, true);
                            
                            img.addEventListener('mousedown', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                e.stopImmediatePropagation();
                            }, true);
                            
                            img.addEventListener('dblclick', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                e.stopImmediatePropagation();
                                return false;
                            }, true);
                            
                            img.addEventListener('dragstart', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                return false;
                            }, true);
                            
                            img.addEventListener('contextmenu', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                return false;
                            }, true);
                        }
                    });
                }
                
                // Setup immediately if DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', setupThumbnails);
                } else {
                    setupThumbnails();
                }
                
                // Also setup after a short delay to catch dynamically loaded content
                setTimeout(setupThumbnails, 100);
            })();
        </script>
    @endpush
@endif


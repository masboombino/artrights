<x-allthepages-layout pageTitle="View Artist Details">
    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 rounded mb-4" style="background-color: #F3EBDD; color: #193948;">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948; border-radius: 20px;">
            <!-- Artist Details -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-4" style="color: #D6BFBF;">Artist Information</h2>
                
                <!-- Personal Information -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold mb-3" style="color: #193948;">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Full Name</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->user?->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Email</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->user?->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Phone</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->user?->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Stage Name</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->stage_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Birth Date</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->birth_date ? \Carbon\Carbon::parse($artist->birth_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Birth Place</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->birth_place ?? 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Address</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Agency Information -->
                <div class="mb-6 pt-4 border-t-2" style="border-color: rgba(255, 227, 227, 0.2);">
                    <h3 class="text-xl font-bold mb-3" style="color: #193948;">Agency Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Agency Name</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->agency ? $artist->agency->agency_name : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Wilaya</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->agency ? $artist->agency->wilaya : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bank Account Information -->
                @if($artist->bank_account_number)
                <div class="mb-6 pt-4 border-t-2" style="border-color: rgba(255, 227, 227, 0.2);">
                    <h3 class="text-xl font-bold mb-3" style="color: #193948;">Bank Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Bank/Postal Account Number</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px; font-family: monospace;">{{ $artist->bank_account_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Full Name on Account</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->full_name_on_account ?? 'N/A' }}</p>
                        </div>
                        @if($artist->bank_account_proof)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Bank Account Proof</label>
                            @php
                                $fileExtension = strtolower(pathinfo($artist->bank_account_proof, PATHINFO_EXTENSION));
                                $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']);
                                $normalizedPath = ltrim($artist->bank_account_proof, '/');
                                $imageUrl = route('media.show', ['path' => $normalizedPath]);
                                $downloadUrl = route('media.show', ['path' => $normalizedPath]);
                            @endphp
                            @if($isImage)
                                <button type="button" class="identity-image-thumb" onclick="openIdentityLightbox('{{ $imageUrl }}', 'bank-proof-{{ $artist->id }}')">
                                    <img src="{{ $imageUrl }}" alt="Bank Account Proof">
                                </button>

                                <div id="bank-proof-{{ $artist->id }}" class="identity-lightbox">
                                    <button type="button" class="lightbox-close-btn" onclick="closeIdentityLightbox('bank-proof-{{ $artist->id }}')">&times;</button>
                                    <button type="button" class="lightbox-rotate-btn lightbox-rotate-left" onclick="rotateIdentityImage('bank-proof-{{ $artist->id }}', -90)">↺</button>
                                    <img id="lightbox-img-bank-proof-{{ $artist->id }}" src="" alt="Bank Account Proof Preview" style="transform: rotate(0deg);">
                                    <button type="button" class="lightbox-rotate-btn lightbox-rotate-right" onclick="rotateIdentityImage('bank-proof-{{ $artist->id }}', 90)">↻</button>
                                </div>
                            @else
                                <div class="p-4 rounded-lg" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 12px;">
                                    <p class="text-base font-semibold mb-2" style="color: #193948;">PDF Document</p>
                                    <a href="{{ $downloadUrl }}" target="_blank" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem; font-weight: 600;">
                                        View PDF Document
                                    </a>
                                </div>
                            @endif
                            
                            <a href="{{ $downloadUrl }}?download=1" download class="inline-block mt-2 rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 0.75rem 1.5rem; font-weight: 600;">
                                Download Proof
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Account Status -->
                <div class="mb-6 pt-4 border-t-2" style="border-color: rgba(255, 227, 227, 0.2);">
                    <h3 class="text-xl font-bold mb-3" style="color: #193948;">Account Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Status</label>
                            <span class="inline-block px-4 py-2 rounded text-sm font-bold" style="
                                @if($artist->status === 'APPROVED') background-color: #D6BFBF; color: #193948;
                                @elseif($artist->status === 'PENDING_VALIDATION') background-color: #F3EBDD; color: #193948;
                                @else background-color: #E76268; color: #193948;
                                @endif
                            ">
                                {{ $artist->status }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Registration Date</label>
                            <p class="text-base p-2 rounded" style="background-color: #ffffff; color: #36454f; border: 2px solid #193948; border-radius: 8px;">{{ $artist->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Identity Document -->
                @if($artist->identity_document)
                    <div class="mb-6 pt-4 border-t-2" style="border-color: rgba(255, 227, 227, 0.2);">
                        <h3 class="text-xl font-bold mb-3" style="color: #193948;">Identity Document</h3>
                        <div class="flex flex-col items-start gap-4">
                            @php
                                $fileExtension = strtolower(pathinfo($artist->identity_document, PATHINFO_EXTENSION));
                                $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']);
                                $normalizedPath = ltrim($artist->identity_document, '/');
                                $imageUrl = route('media.show', ['path' => $normalizedPath]);
                                $downloadUrl = route('media.show', ['path' => $normalizedPath]);
                            @endphp
                            
                            @if($isImage)
                                <style>
                                    .identity-image-thumb {
                                        position: relative;
                                        padding: 0;
                                        border: 2px solid #193948;
                                        border-radius: 8px;
                                        overflow: hidden;
                                        cursor: pointer;
                                        background: transparent;
                                        transition: transform 0.2s, box-shadow 0.2s;
                                        max-width: 300px;
                                        max-height: 300px;
                                    }

                                    .identity-image-thumb:hover {
                                        transform: translateY(-4px);
                                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                                    }

                                    .identity-image-thumb img {
                                        width: 100%;
                                        height: 100%;
                                        object-fit: contain;
                                        display: block;
                                    }

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
                                
                                <button type="button" class="identity-image-thumb" onclick="openIdentityLightbox('{{ $imageUrl }}', 'identity-lightbox-{{ $artist->id }}')">
                                    <img src="{{ $imageUrl }}" alt="Identity Document">
                                </button>

                                <div id="identity-lightbox-{{ $artist->id }}" class="identity-lightbox">
                                    <button type="button" class="lightbox-close-btn" onclick="closeIdentityLightbox('identity-lightbox-{{ $artist->id }}')">&times;</button>
                                    <button type="button" class="lightbox-rotate-btn lightbox-rotate-left" onclick="rotateIdentityImage('identity-lightbox-img-{{ $artist->id }}', -90)">↺</button>
                                    <img id="identity-lightbox-img-{{ $artist->id }}" src="" alt="Identity Document Preview" style="transform: rotate(0deg);">
                                    <button type="button" class="lightbox-rotate-btn lightbox-rotate-right" onclick="rotateIdentityImage('identity-lightbox-img-{{ $artist->id }}', 90)">↻</button>
                                </div>

                                <script>
                                    window.identityRotation = window.identityRotation || {};

                                    function openIdentityLightbox(imageUrl, lightboxId) {
                                        // If lightboxId is not provided, try to guess the old default (compatibility)
                                        if (!lightboxId) {
                                            lightboxId = 'identity-lightbox-{{ $artist->id }}';
                                        }
                                        
                                        const lightbox = document.getElementById(lightboxId);
                                        // Try to find the image inside the lightbox
                                        const img = lightbox ? lightbox.querySelector('img') : null;
                                        
                                        if (lightbox && img) {
                                            img.src = imageUrl;
                                            img.style.transform = 'rotate(0deg)';
                                            window.identityRotation[img.id] = 0;
                                            lightbox.style.display = 'flex';
                                        }
                                    }

                                    function closeIdentityLightbox(lightboxId) {
                                        // Compatibility wrapper
                                        if (lightboxId && !lightboxId.startsWith('identity-lightbox') && !lightboxId.startsWith('bank-proof')) {
                                            // Assume it's just the ID part passed by old calls
                                            lightboxId = 'identity-lightbox-' + lightboxId;
                                        }

                                        const lightbox = document.getElementById(lightboxId);
                                        if (lightbox) {
                                            lightbox.style.display = 'none';
                                        }
                                    }

                                    function rotateIdentityImage(imageId, degrees) {
                                        // Compatibility wrapper
                                        if (imageId && !imageId.startsWith('identity-lightbox-img') && !imageId.startsWith('lightbox-img-bank-proof')) {
                                            // Assume it's just the artist ID passed by old calls
                                            imageId = 'identity-lightbox-img-' + imageId;
                                        }

                                        if (!window.identityRotation[imageId]) {
                                            window.identityRotation[imageId] = 0;
                                        }
                                        window.identityRotation[imageId] += degrees;
                                        
                                        const img = document.getElementById(imageId);
                                        if (img) {
                                            img.style.transform = 'rotate(' + window.identityRotation[imageId] + 'deg)';
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
                            @else
                                <div class="p-4 rounded-lg" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 12px;">
                                    <p class="text-base font-semibold mb-2" style="color: #193948;">PDF Document</p>
                                    <a href="{{ $downloadUrl }}" target="_blank" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem; font-weight: 600;">
                                        View PDF Document
                                    </a>
                                </div>
                            @endif
                            
                            <a href="{{ $downloadUrl }}?download=1" download class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 0.75rem 1.5rem; font-weight: 600;">
                                Download Document
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            @if($artist->status === 'PENDING_VALIDATION')
                <div class="pt-6 border-t-2" style="border-color: rgba(255, 227, 227, 0.2);">
                    <div class="flex flex-wrap gap-4 justify-center">
                        <form action="{{ route('admin.approve-artist', $artist->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 2rem; font-weight: 600; font-size: 1rem;">
                                Approve Artist
                            </button>
                        </form>
                        <button type="button" onclick="openRejectModal()" class="rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #E76268; color: #193948; padding: 0.75rem 2rem; font-weight: 600; font-size: 1rem;">
                            Reject Artist
                        </button>
                        
                        <!-- Rejection Modal -->
                        <div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; justify-content: center; align-items: center;">
                            <div style="background: white; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; border: 3px solid #E76268;">
                                <h3 style="color: #E76268; font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">Reject Artist Account</h3>
                                <p style="color: #555; margin-bottom: 1.5rem;">Are you sure you want to reject this artist? This will permanently delete their account. <strong style="color: #E76268;">You must provide a reason for rejection.</strong></p>
                                
                                @if($errors->any())
                                    <div style="background-color: #FFEBEE; border: 2px solid #C62828; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                                        <ul style="margin: 0; padding-left: 1.5rem; color: #C62828;">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <form action="{{ route('admin.reject-artist', $artist->id) }}" method="POST">
                                    @csrf
                                    <div style="margin-bottom: 1.5rem;">
                                        <label for="rejection_reason" style="display: block; color: #193948; font-weight: 600; margin-bottom: 0.5rem;">
                                            Rejection Reason <span style="color: #E76268;">*</span>
                                        </label>
                                        <textarea id="rejection_reason" name="rejection_reason" rows="5" required minlength="10" maxlength="1000" style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 8px; font-family: inherit; resize: vertical;" placeholder="Please explain why this account is being rejected (minimum 10 characters)...">{{ old('rejection_reason') }}</textarea>
                                        <small style="color: #36454f; font-size: 0.85rem; display: block; margin-top: 0.5rem;">This reason will be sent to the user via email. Minimum 10 characters required.</small>
                                    </div>
                                    
                                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                                        <button type="button" onclick="closeRejectModal()" class="rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #36454f; color: #4FADC0; padding: 0.75rem 1.5rem; font-weight: 600; border: none; cursor: pointer;">
                                            Cancel
                                        </button>
                                        <button type="submit" class="rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #E76268; color: #193948; padding: 0.75rem 1.5rem; font-weight: 600; border: none; cursor: pointer;">
                                            Confirm Rejection
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <script>
                            function openRejectModal() {
                                document.getElementById('rejectModal').style.display = 'flex';
                            }
                            
                            function closeRejectModal() {
                                document.getElementById('rejectModal').style.display = 'none';
                            }
                            
                            // Close modal when clicking outside
                            document.getElementById('rejectModal')?.addEventListener('click', function(e) {
                                if (e.target === this) {
                                    closeRejectModal();
                                }
                            });
                        </script>
                        <a href="{{ route('admin.manage-users') }}" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #36454f; color: #4FADC0; padding: 0.75rem 2rem; font-weight: 600; font-size: 1rem;">
                            Back to List
                        </a>
                    </div>
                </div>
            @else
                <div class="pt-6 border-t-2" style="border-color: rgba(255, 227, 227, 0.2);">
                    <div class="flex justify-center">
                        <a href="{{ route('admin.manage-users') }}" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #36454f; color: #4FADC0; padding: 0.75rem 2rem; font-weight: 600; font-size: 1rem;">
                            Back to List
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-allthepages-layout>


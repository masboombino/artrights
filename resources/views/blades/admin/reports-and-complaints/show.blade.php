<x-allthepages-layout pageTitle="View Complaint">
    <div class="space-y-6">
        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948; border-radius: 20px;">
            <!-- Complaint Details -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-4" style="color: #D6BFBF;">Complaint Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    @if($complaint->artist)
                        <div class="text-center p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Artist</label>
                            <p class="text-base" style="color: #36454f;">{{ $complaint->artist->user->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-center p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                            <label class="block text-sm font-bold mb-1" style="color: #193948;">Agency</label>
                            <p class="text-base" style="color: #36454f;">{{ $complaint->artist->agency ? $complaint->artist->agency->agency_name . ' - ' . $complaint->artist->agency->wilaya : 'N/A' }}</p>
                        </div>
                    @endif
                    <div class="text-center p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                        <label class="block text-sm font-bold mb-1" style="color: #193948;">Status</label>
                        <span class="inline-block px-3 py-1 rounded text-sm font-bold" style="background-color: #193948; color: #4FADC0;">
                            {{ $complaint->status }}
                        </span>
                    </div>
                    <div class="text-center p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                        <label class="block text-sm font-bold mb-1" style="color: #193948;">Subject</label>
                        <p class="text-base" style="color: #36454f;">{{ $complaint->subject }}</p>
                    </div>
                    <div class="text-center p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                        <label class="block text-sm font-bold mb-1" style="color: #193948;">Submitted</label>
                        <p class="text-base" style="color: #36454f;">{{ $complaint->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="text-center p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                        <label class="block text-sm font-bold mb-1" style="color: #193948;">Location</label>
                        @if($complaint->location_link)
                            <a href="{{ $complaint->location_link }}" target="_blank" style="color: #D6BFBF;">Open Map</a>
                        @else
                            <p class="text-base" style="color: #36454f;">N/A</p>
                        @endif
                    </div>
                    <div class="text-center p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                        <label class="block text-sm font-bold mb-1" style="color: #193948;">Assigned Gestionnaire</label>
                        <p class="text-base" style="color: #36454f;">{{ $complaint->gestionnaire->name ?? 'Not assigned' }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" style="color: #193948;">Message</label>
                    <div class="p-4 rounded-lg" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 12px;">
                        <p class="text-base leading-relaxed whitespace-pre-wrap" style="color: #36454f; line-height: 1.8;">
                            {{ $complaint->message }}
                        </p>
                    </div>
                </div>

                @if($complaint->complaint_type === 'ARTIST_TO_ADMIN')
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2" style="color: #193948;">Assign to Gestionnaire</h3>
                        <form method="POST" action="{{ route('admin.complaints.assign', $complaint->id) }}" class="flex flex-col md:flex-row gap-3 items-start">
                            @csrf
                            <select name="gestionnaire_id" class="rounded border p-2 w-full md:w-64">
                                <option value="">Select gestionnaire</option>
                                @foreach($gestionnaires as $gestionnaire)
                                    <option value="{{ $gestionnaire->id }}" @selected($complaint->gestionnaire_id === $gestionnaire->id)>{{ $gestionnaire->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #193948; color: #4FADC0;">Assign</button>
                        </form>
                    </div>
                @endif

                <!-- Complaint Images -->
                @if($complaint->images && count($complaint->images) > 0)
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2" style="color: #193948;">Complaint Images</label>
                        @include('blades.partials.complaint-gallery', [
                            'galleryId' => 'admin-complaint-' . $complaint->id,
                            'images' => $complaint->images
                        ])
                    </div>
                @endif
            </div>

            <!-- Admin Response Section -->
            @if($complaint->admin_response)
                <div class="mb-6 pt-6 border-t-2" style="border-color: rgba(255, 227, 227, 0.2);">
                    <h3 class="text-xl font-bold mb-4" style="color: #193948;">Admin Response</h3>
                    <div class="p-4 rounded-lg mb-4" style="background-color: rgba(255, 227, 227, 0.1); border: 2px solid #D6BFBF; border-radius: 12px;">
                        <p class="text-base leading-relaxed whitespace-pre-wrap" style="color: #36454f; line-height: 1.8;">
                            {{ $complaint->admin_response }}
                        </p>
                    </div>
                    
                    @if($complaint->admin_response_images && count($complaint->admin_response_images) > 0)
                        <div class="mb-4">
                            <label class="block text-sm font-bold mb-2" style="color: #193948;">Response Images</label>
                            @include('blades.partials.complaint-gallery', [
                                'galleryId' => 'admin-response-' . $complaint->id,
                                'images' => $complaint->admin_response_images
                            ])
                        </div>
                    @endif

                    <div class="text-sm" style="color: #36454f;">
                        <strong>Responded by:</strong> {{ $complaint->admin->name ?? 'N/A' }}<br>
                        <strong>Date:</strong> {{ $complaint->updated_at->format('M d, Y H:i') }}
                    </div>
                </div>
            @else
                <!-- Response Form -->
                <div class="pt-6 border-t-2" style="border-color: rgba(255, 227, 227, 0.2);">
                    <h3 class="text-xl font-bold mb-4" style="color: #193948;">Respond to Complaint</h3>
                    <form action="{{ route('admin.complaints.respond', $complaint->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="admin_response" class="block text-sm font-bold mb-2" style="color: #193948;">Response Message *</label>
                            <textarea name="admin_response" id="admin_response" rows="6" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                style="background-color: white; color: #193948;"></textarea>
                            @error('admin_response')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="admin_response_images" class="block text-sm font-bold mb-2" style="color: #193948;">Response Images (Optional, Max 5 images, 10MB each)</label>
                            <input type="file" name="admin_response_images[]" id="admin_response_images" multiple accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                style="background-color: white; color: #193948;">
                            @error('admin_response_images.*')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem; font-weight: 600;">
                                Send Response
                            </button>
                            <a href="{{ route('admin.complaints.index') }}" class="rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #36454f; color: #4FADC0; padding: 0.75rem 1.5rem; font-weight: 600;">
                                Back to List
                            </a>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Lightbox JavaScript -->
    <script>
        function openLightbox(type, index) {
            const lightbox = document.getElementById('lightbox-' + type);
            const parts = type.split('-');
            const id = parts[parts.length - 1];
            const prefix = type.startsWith('complaint') ? 'complaintImages' : 'responseImages';
            const images = window[prefix + id];
            
            if (lightbox && images && images.length > 0) {
                const indexKey = (type.startsWith('complaint') ? 'currentImageIndex' : 'currentResponseImageIndex') + id;
                window[indexKey] = index;
                updateLightboxImage(type, index);
                lightbox.classList.remove('hidden');
            }
        }

        function closeLightbox(type) {
            const lightbox = document.getElementById('lightbox-' + type);
            if (lightbox) {
                lightbox.classList.add('hidden');
            }
        }

        function changeImage(type, direction) {
            const parts = type.split('-');
            const id = parts[parts.length - 1];
            const prefix = type.startsWith('complaint') ? 'complaintImages' : 'responseImages';
            const images = window[prefix + id];
            
            if (!images || images.length === 0) return;
            
            const indexKey = (type.startsWith('complaint') ? 'currentImageIndex' : 'currentResponseImageIndex') + id;
            let currentIndex = window[indexKey] || 0;
            currentIndex += direction;
            
            if (currentIndex < 0) {
                currentIndex = images.length - 1;
            } else if (currentIndex >= images.length) {
                currentIndex = 0;
            }
            
            window[indexKey] = currentIndex;
            updateLightboxImage(type, currentIndex);
        }

        function updateLightboxImage(type, index) {
            const parts = type.split('-');
            const id = parts[parts.length - 1];
            const prefix = type.startsWith('complaint') ? 'complaintImages' : 'responseImages';
            const images = window[prefix + id];
            
            if (!images || !images[index]) return;
            
            const img = document.getElementById('lightbox-img-' + type);
            const caption = document.getElementById('lightbox-caption-' + type);
            
            if (img) {
                img.src = '{{ asset("storage/") }}/' + images[index];
            }
            
            if (caption) {
                caption.textContent = 'Image ' + (index + 1) + ' of ' + images.length;
            }
        }

        // Close lightbox on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id^="lightbox-"]').forEach(lightbox => {
                    if (!lightbox.classList.contains('hidden')) {
                        lightbox.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-allthepages-layout>


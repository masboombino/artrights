<x-allthepages-layout pageTitle="Message Details">
    <div style="padding: 5px; margin: 5px;">
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('superadmin.messages.index') }}" style="padding: 10px 20px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                ← Back to Messages
            </a>
        </div>

        @php
            $message = $complaint ?? null;
            if (!$message) {
                $message = \App\Models\Complain::with(['sender', 'targetUser', 'admin', 'gestionnaire', 'artist.user', 'agentProfile.user', 'agency'])->findOrFail($id ?? request()->route('id'));
            }
        @endphp

        <div class="page-container" style="margin-bottom: 1.5rem;">
            <!-- Header -->
            <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 15px; padding-bottom: 1.5rem; margin-bottom: 1.5rem; border-bottom: 3px solid #D6BFBF;">
                <div style="flex: 1;">
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 1rem;">
                        <span style="padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; background-color: {{ $message->type === 'COMPLAINT' ? '#E76268' : '#10b981' }}; color: white;">
                            {{ $message->type === 'COMPLAINT' ? '⚠️ Complaint' : '📊 Report' }}
                        </span>
                        <span style="padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; background-color: 
                            @if($message->status === 'PENDING') #f59e0b 
                            @elseif($message->status === 'RESOLVED') #10b981 
                            @else #6366f1 @endif; color: white;">
                            {{ str_replace('_', ' ', $message->status) }}
                        </span>
                    </div>
                    <h1 style="color: #193948; font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $message->subject }}</h1>
                    <div style="display: flex; gap: 20px; flex-wrap: wrap; font-size: 0.9rem; color: #193948; opacity: 0.8;">
                        <span>From: <strong>{{ $message->sender?->name ?? ucfirst($message->sender_role ?? 'Unknown') }}</strong></span>
                        <span>•</span>
                        <span>Date: {{ $message->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div style="margin-bottom: 1.5rem;">
                <h3 style="color: #193948; font-weight: 700; margin-bottom: 0.75rem; font-size: 1.1rem;">💬 Message:</h3>
                <div style="background-color: white; border: 2px solid #193948; border-radius: 0.5rem; padding: 1.5rem;">
                    <p style="color: #193948; white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $message->message }}</p>
                </div>
            </div>

            <!-- Images -->
            @if($message->images && count($message->images) > 0)
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="color: #193948; font-weight: 700; margin-bottom: 0.75rem; font-size: 1.1rem;">📷 Attachments:</h3>
                    <div style="background-color: white; border: 2px solid #193948; border-radius: 0.5rem; padding: 1rem;">
                        @include('blades.partials.complaint-gallery', [
                            'galleryId' => 'message-' . $message->id,
                            'images' => $message->images
                        ])
                    </div>
                </div>
            @endif

            <!-- Location -->
            @if($message->location_link)
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="color: #193948; font-weight: 700; margin-bottom: 0.75rem; font-size: 1.1rem;">📍 Location:</h3>
                    <a href="{{ $message->location_link }}" target="_blank" style="padding: 10px 20px; background-color: #10b981; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                        🗺️ Open Location
                    </a>
                </div>
            @endif

            <!-- Response Section -->
            @php
                $responseField = 'super_admin_response';
                $responseImagesField = 'super_admin_response_images';
                $responseValue = $message->{$responseField};
                $responseImages = $message->{$responseImagesField} ?? [];
            @endphp

            @if($responseValue)
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 3px solid #D6BFBF;">
                    <h3 style="color: #193948; font-weight: 700; margin-bottom: 1rem; font-size: 1.25rem;">✅ Response:</h3>
                    <div style="background-color: #D1FAE5; border: 2px solid #10b981; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1rem;">
                        <p style="color: #193948; white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $responseValue }}</p>
                    </div>

                    @if(is_array($responseImages) && count($responseImages) > 0)
                        <div style="background-color: white; border: 2px solid #193948; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                            <h4 style="color: #193948; font-weight: 600; margin-bottom: 1rem;">Response Images:</h4>
                            @include('blades.partials.complaint-gallery', [
                                'galleryId' => 'response-' . $message->id,
                                'images' => $responseImages
                            ])
                        </div>
                    @endif
                </div>
            @else
                <!-- Response Form -->
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 3px solid #D6BFBF;">
                    <h3 style="color: #193948; font-weight: 700; margin-bottom: 1rem; font-size: 1.25rem;">✍️ Respond to Message:</h3>
                    <form action="{{ route('superadmin.messages.respond', $message->id) }}" method="POST" enctype="multipart/form-data" style="background-color: #F3EBDD; padding: 1.5rem; border-radius: 0.5rem; border: 2px solid #193948;">
                        @csrf
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; color: #193948; font-weight: 700; margin-bottom: 0.5rem;">Your Response *</label>
                            <textarea name="super_admin_response" rows="6" required style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white; resize: vertical;"></textarea>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; color: #193948; font-weight: 700; margin-bottom: 0.5rem;">Response Images (Optional, Max 5)</label>
                            <input type="file" name="super_admin_response_images[]" multiple accept="image/*" style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; background-color: white;">
                        </div>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button type="submit" style="padding: 12px 24px; background-color: #10b981; color: white; border-radius: 0.5rem; border: none; font-weight: 600; cursor: pointer;">
                                ✅ Send Response
                            </button>
                            <form action="{{ route('superadmin.messages.resolve', $message->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="padding: 12px 24px; background-color: #6366f1; color: white; border-radius: 0.5rem; border: none; font-weight: 600; cursor: pointer;">
                                    ✅ Mark as Resolved
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-allthepages-layout>







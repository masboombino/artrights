<x-allthepages-layout pageTitle="Message Details">
    <div style="padding: 5px; margin: 5px;">
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('artist.messages.index') }}" style="padding: 10px 20px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">
                ← Back to Messages
            </a>
        </div>

        <div class="page-container">
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
                        <span>To: <strong>{{ ucfirst(str_replace('_', ' ', $message->target_role ?? 'admin')) }}</strong></span>
                        <span>•</span>
                        <span>Date: {{ $message->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h3 style="color: #193948; font-weight: 700; margin-bottom: 0.75rem;">💬 Message:</h3>
                <div style="background-color: white; border: 2px solid #193948; border-radius: 0.5rem; padding: 1.5rem;">
                    <p style="color: #193948; white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $message->message }}</p>
                </div>
            </div>

            @if($message->images && count($message->images) > 0)
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="color: #193948; font-weight: 700; margin-bottom: 0.75rem;">📷 Attachments:</h3>
                    <div style="background-color: white; border: 2px solid #193948; border-radius: 0.5rem; padding: 1rem;">
                        @include('blades.partials.complaint-gallery', [
                            'galleryId' => 'message-' . $message->id,
                            'images' => $message->images,
                            'useStorageRoute' => true
                        ])
                    </div>
                </div>
            @endif

            @php
                $responseField = $message->target_role === 'gestionnaire' ? 'gestionnaire_response' : 'admin_response';
                $responseImagesField = $message->target_role === 'gestionnaire' ? 'gestionnaire_response_images' : 'admin_response_images';
                $responseValue = $message->{$responseField};
                $responseImages = $message->{$responseImagesField} ?? [];
            @endphp

            @if($responseValue)
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 3px solid #D6BFBF;">
                    <h3 style="color: #193948; font-weight: 700; margin-bottom: 1rem;">✅ Response:</h3>
                    <div style="background-color: #D1FAE5; border: 2px solid #10b981; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1rem;">
                        <p style="color: #193948; white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $responseValue }}</p>
                    </div>

                    @if(is_array($responseImages) && count($responseImages) > 0)
                        <div style="background-color: white; border: 2px solid #193948; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                            <h4 style="color: #193948; font-weight: 600; margin-bottom: 1rem;">Response Images:</h4>
                            @include('blades.partials.complaint-gallery', [
                                'galleryId' => 'response-' . $message->id,
                                'images' => $responseImages,
                                'useStorageRoute' => true
                            ])
                        </div>
                    @endif
                </div>
            @else
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px dashed rgba(255, 227, 227, 0.3);">
                    <p style="color: #193948; font-size: 0.9rem; opacity: 0.7;">⏳ Waiting for {{ $message->target_role === 'gestionnaire' ? 'gestionnaire' : 'admin' }} response...</p>
                </div>
            @endif
        </div>
    </div>
</x-allthepages-layout>







<x-allthepages-layout pageTitle="Complaint Details">
    <div style="padding: 1rem;">
        <a href="{{ route('superadmin.complaints.index') }}" style="display:inline-block; margin-bottom:1rem; padding:0.5rem 0.9rem; border:2px solid #193948; border-radius:10px; text-decoration:none; color:#193948; font-weight:700; background:#fff;">Back</a>

        <div class="stat-card" style="padding: 1rem;">
            <div style="display:flex; justify-content:space-between; gap:0.8rem; flex-wrap:wrap; margin-bottom:1rem; border-bottom:2px solid #D6BFBF; padding-bottom:0.8rem;">
                <div>
                    <div style="display:flex; gap:0.4rem; flex-wrap:wrap; margin-bottom:0.45rem;">
                        <span style="padding:0.24rem 0.58rem; border-radius:999px; font-size:0.76rem; font-weight:700; color:#fff; background:{{ $message->type === 'COMPLAINT' ? '#E76268' : '#10b981' }};">{{ $message->type === 'COMPLAINT' ? 'Complaint' : 'Report' }}</span>
                        <span style="padding:0.24rem 0.58rem; border-radius:999px; font-size:0.76rem; font-weight:700; color:#fff; background:@if($message->status === 'PENDING') #f59e0b @elseif($message->status === 'RESOLVED') #10b981 @else #6366f1 @endif;">{{ str_replace('_', ' ', $message->status) }}</span>
                    </div>
                    <h2 style="margin:0; color:#193948;">{{ $message->subject }}</h2>
                    <p style="margin:0.35rem 0 0; color:#36454f; font-size:0.9rem;">From {{ $message->sender?->name ?? ucfirst($message->sender_role ?? 'Unknown') }} • {{ $message->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">Message</label>
                <div style="background:#fff; border:2px solid #193948; border-radius:10px; padding:0.85rem; color:#193948; white-space:pre-wrap;">{{ $message->message }}</div>
            </div>

            @if($message->images && count($message->images) > 0)
                <div style="margin-bottom:1rem;">
                    <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">Attachments</label>
                    @include('blades.partials.complaint-gallery', [
                        'galleryId' => 'complaint-' . $message->id,
                        'images' => $message->images
                    ])
                </div>
            @endif

            @if($message->location_link)
                <div style="margin-bottom:1rem;">
                    <a href="{{ $message->location_link }}" target="_blank" style="display:inline-block; padding:0.45rem 0.8rem; border-radius:10px; text-decoration:none; background:#193948; color:#4FADC0; font-weight:700;">Open Location</a>
                </div>
            @endif

            @php
                $responseValue = $message->super_admin_response;
                $responseImages = $message->super_admin_response_images ?? [];
            @endphp

            @if($responseValue)
                <div style="margin-top:1.2rem; padding-top:1rem; border-top:2px solid #D6BFBF;">
                    <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">Super Admin Response</label>
                    <div style="background:#D1FAE5; border:2px solid #10b981; border-radius:10px; padding:0.85rem; color:#193948; white-space:pre-wrap;">{{ $responseValue }}</div>
                    @if(is_array($responseImages) && count($responseImages) > 0)
                        <div style="margin-top:0.8rem;">
                            @include('blades.partials.complaint-gallery', [
                                'galleryId' => 'response-' . $message->id,
                                'images' => $responseImages
                            ])
                        </div>
                    @endif
                </div>
            @else
                <div style="margin-top:1.2rem; padding-top:1rem; border-top:2px solid #D6BFBF;">
                    <form action="{{ route('superadmin.complaints.respond', $message->id) }}" method="POST" enctype="multipart/form-data" style="margin-bottom:0.75rem;">
                        @csrf
                        <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.45rem;">Write Response</label>
                        <textarea name="super_admin_response" rows="5" required style="width:100%; padding:0.75rem; border:2px solid #193948; border-radius:10px; color:#193948; background:#fff; resize:vertical;"></textarea>
                        <label style="display:block; color:#193948; font-weight:700; margin:0.7rem 0 0.4rem;">Response Images (Optional)</label>
                        <input type="file" name="super_admin_response_images[]" multiple accept="image/*" style="width:100%; padding:0.65rem; border:2px solid #193948; border-radius:10px; background:#fff;">
                        <div style="margin-top:0.75rem;">
                            <button type="submit" style="padding:0.55rem 0.95rem; border:none; border-radius:10px; background:#10b981; color:#fff; font-weight:700; cursor:pointer;">Send Response</button>
                        </div>
                    </form>
                    <form action="{{ route('superadmin.complaints.resolve', $message->id) }}" method="POST">
                        @csrf
                        <button type="submit" style="padding:0.55rem 0.95rem; border:none; border-radius:10px; background:#6366f1; color:#fff; font-weight:700; cursor:pointer;">Mark as Resolved</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-allthepages-layout>


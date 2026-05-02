<x-allthepages-layout pageTitle="Complaint Details">
    <div style="padding:1rem;">
        <div class="stat-card" style="padding:1rem; max-width:980px; margin:0 auto;">
            <div style="display:flex; justify-content:space-between; gap:0.8rem; flex-wrap:wrap; margin-bottom:1rem;">
                <div>
                    <span style="display:inline-block; padding:0.24rem 0.58rem; border-radius:999px; color:#fff; font-size:0.76rem; background:#E76268;">Complaint</span>
                    <h2 style="margin:0.45rem 0 0; color:#193948;">{{ $complaint->subject }}</h2>
                    <p style="margin:0.35rem 0 0; color:#36454f; font-size:0.9rem;">From: {{ $complaint->sender?->name ?? ucfirst($complaint->sender_role ?? 'Unknown') }}</p>
                </div>
                <span style="padding:0.24rem 0.58rem; border-radius:999px; color:#fff; font-size:0.76rem; background:@if($complaint->status === 'PENDING') #f59e0b @elseif($complaint->status === 'RESOLVED') #10b981 @else #6366f1 @endif;">{{ str_replace('_', ' ', $complaint->status) }}</span>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">Message</label>
                <div style="background:#fff; border:2px solid #193948; border-radius:10px; padding:0.85rem; color:#193948; white-space:pre-wrap;">{{ $complaint->message }}</div>
            </div>

            @if($complaint->images && count($complaint->images) > 0)
                <div style="margin-bottom:1rem;">
                    <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">Attachments</label>
                    @include('blades.partials.complaint-gallery', [
                        'galleryId' => 'admin-complaint-' . $complaint->id,
                        'images' => $complaint->images
                    ])
                </div>
            @endif

            @if($complaint->admin_response)
                <div style="margin-top:1rem; border-top:2px solid #D6BFBF; padding-top:1rem;">
                    <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">Your Response</label>
                    <div style="background:#D1FAE5; border:2px solid #10b981; border-radius:10px; padding:0.85rem; color:#193948; white-space:pre-wrap;">{{ $complaint->admin_response }}</div>
                </div>
            @else
                <div style="margin-top:1rem; border-top:2px solid #D6BFBF; padding-top:1rem;">
                    <form action="{{ route('admin.complaints.respond', $complaint->id) }}" method="POST">
                        @csrf
                        <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">Respond</label>
                        <textarea name="admin_response" rows="5" required style="width:100%; padding:0.75rem; border:2px solid #193948; border-radius:10px; color:#193948;"></textarea>
                        <div style="margin-top:0.7rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
                            <button type="submit" style="padding:0.55rem 0.95rem; border:none; border-radius:10px; background:#193948; color:#4FADC0; font-weight:700;">Send Response</button>
                            <button type="submit" formaction="{{ route('admin.complaints.resolve', $complaint->id) }}" style="padding:0.55rem 0.95rem; border:none; border-radius:10px; background:#10b981; color:#fff; font-weight:700;">Resolve</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-allthepages-layout>

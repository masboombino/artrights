<x-allthepages-layout pageTitle="{{ $complaint->type === 'REPORT' ? 'Report Details' : 'Complaint Details' }}">
    <div style="padding: 1rem;">
        @if(session('success'))
            <div class="p-4 rounded mb-4" style="background-color: #D1FAE5; color: #193948; border: 2px solid #10b981;">
                {{ session('success') }}
            </div>
        @endif

        <div class="stat-card" style="padding: 1rem; max-width: 980px; margin: 0 auto;">
            <div style="display:flex; justify-content:space-between; gap:0.8rem; flex-wrap:wrap; margin-bottom:1rem;">
                <div>
                    <span style="display:inline-block; margin-bottom:0.45rem; padding:0.25rem 0.55rem; border-radius:999px; color:white; font-size:0.78rem; background:{{ $complaint->type === 'COMPLAINT' ? '#E76268' : '#10b981' }};">
                        {{ $complaint->type === 'COMPLAINT' ? 'Complaint' : 'Report' }}
                    </span>
                    <h2 style="margin:0; color:#193948;">{{ $complaint->subject }}</h2>
                    <p style="margin:0.45rem 0 0; color:#36454f; font-size:0.9rem;">
                        To: {{ ucfirst($complaint->target_role ?? 'admin') }} {{ $complaint->targetUser ? ('- '.$complaint->targetUser->name) : '' }}
                    </p>
                    <p style="margin:0.2rem 0 0; color:#36454f; font-size:0.82rem;">Submitted: {{ $complaint->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <div>
                    <span style="padding:0.35rem 0.65rem; border-radius:999px; color:white; font-size:0.78rem; background:
                    @if($complaint->status === 'PENDING') #f59e0b @elseif($complaint->status === 'RESOLVED') #10b981 @else #6366f1 @endif;">
                        {{ str_replace('_', ' ', $complaint->status) }}
                    </span>
                </div>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">Message</label>
                <div style="background:white; border:2px solid #193948; border-radius:0.5rem; padding:0.9rem; color:#36454f; white-space:pre-wrap;">{{ $complaint->message }}</div>
            </div>

            @if($complaint->location_link)
                <div style="margin-bottom:1rem;">
                    <a href="{{ $complaint->location_link }}" target="_blank" style="display:inline-block; padding:0.45rem 0.8rem; border-radius:0.45rem; text-decoration:none; background:#193948; color:#4FADC0; font-weight:700;">
                        Open Location
                    </a>
                </div>
            @endif

            @if($complaint->images && count($complaint->images) > 0)
                <div style="margin-bottom:1rem;">
                    <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">Attachments</label>
                    @include('blades.partials.complaint-gallery', [
                        'galleryId' => 'agent-item-'.$complaint->id,
                        'images' => $complaint->images
                    ])
                </div>
            @endif

            @php
                $targetResponseField = $complaint->target_role === 'gestionnaire' ? 'gestionnaire_response' : 'admin_response';
                $targetResponseImagesField = $complaint->target_role === 'gestionnaire' ? 'gestionnaire_response_images' : 'admin_response_images';
                $targetResponseLabel = $complaint->target_role === 'gestionnaire' ? 'Gestionnaire Response' : 'Admin Response';
            @endphp

            @if($complaint->{$targetResponseField})
                <div style="margin-top:1.2rem; border-top:2px solid rgba(25,57,72,0.2); padding-top:1rem;">
                    <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">{{ $targetResponseLabel }}</label>
                    <div style="background:white; border:2px solid #D6BFBF; border-radius:0.5rem; padding:0.9rem; color:#36454f; white-space:pre-wrap;">{{ $complaint->{$targetResponseField} }}</div>
                    @if(is_array($complaint->{$targetResponseImagesField}) && count($complaint->{$targetResponseImagesField}) > 0)
                        <div style="margin-top:0.8rem;">
                            <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.4rem;">Response Attachments</label>
                            @include('blades.partials.complaint-gallery', [
                                'galleryId' => 'agent-target-response-'.$complaint->id,
                                'images' => $complaint->{$targetResponseImagesField}
                            ])
                        </div>
                    @endif
                </div>
            @else
                <div style="margin-top:1.2rem; border-top:2px dashed rgba(25,57,72,0.2); padding-top:1rem; color:#36454f;">
                    Waiting for {{ $complaint->target_role === 'gestionnaire' ? 'gestionnaire' : 'admin' }} response.
                </div>
            @endif

            <div style="margin-top:1.1rem;">
                <a href="{{ route('agent.complaints.index') }}" style="display:inline-block; padding:0.45rem 0.8rem; border-radius:0.45rem; text-decoration:none; background:#193948; color:#4FADC0; font-weight:700;">Back</a>
            </div>
        </div>
    </div>
</x-allthepages-layout>


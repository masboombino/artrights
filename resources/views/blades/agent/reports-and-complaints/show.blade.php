<x-allthepages-layout pageTitle="{{ $complaint->type === 'REPORT' ? 'Report Details' : 'Complaint Details' }}">
    <div class="space-y-6" style="padding: 1rem;">
        @if(session('success'))
            <div class="p-4 rounded mb-4" style="background-color: #D1FAE5; color: #193948; border: 2px solid #10b981;">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948; max-width: 900px; margin: 0 auto;">
            <div class="mb-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                    <div>
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 0.5rem;">
                            <span class="px-3 py-1 text-xs font-semibold rounded" style="background-color: {{ $complaint->type === 'COMPLAINT' ? '#E76268' : '#10b981' }}; color: white;">
                                {{ $complaint->type === 'COMPLAINT' ? '⚠️ Complaint' : '📊 Report' }}
                            </span>
                            <h2 class="text-2xl font-bold" style="color: #193948; margin: 0;">{{ $complaint->subject }}</h2>
                        </div>
                        <p class="text-sm" style="color: #36454f; margin-top: 0.5rem;">To: {{ ucfirst($complaint->target_role ?? 'admin') }}</p>
                        @if($complaint->targetUser)
                            <p class="text-sm" style="color: #36454f;">{{ $complaint->targetUser->name }}</p>
                        @endif
                    </div>
                    <span class="px-4 py-2 rounded text-sm font-bold" style="background-color: 
                        @if($complaint->status === 'PENDING') #f59e0b 
                        @elseif($complaint->status === 'RESOLVED') #10b981 
                        @elseif($complaint->status === 'IN_PROGRESS') #6366f1 
                        @else #193948 @endif; color: white;">
                        {{ str_replace('_', ' ', $complaint->status) }}
                    </span>
                </div>
                <p class="text-xs" style="color: #36454f;">Submitted on {{ $complaint->created_at->format('Y-m-d H:i') }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2" style="color: #193948;">Message</label>
                <div class="p-4 rounded-lg" style="background-color: #ffffff; border: 2px solid #193948;">
                    <p class="whitespace-pre-wrap" style="color: #36454f;">{{ $complaint->message }}</p>
                </div>
            </div>

            @if($complaint->location_link)
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" style="color: #193948;">Location</label>
                    <a href="{{ $complaint->location_link }}" target="_blank" class="inline-block px-4 py-2 rounded" style="background-color: #193948; color: #4FADC0; text-decoration: none;">
                        📍 Open Map
                    </a>
                </div>
            @endif

            @if($complaint->images && count($complaint->images) > 0)
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2" style="color: #193948;">Attachments</label>
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
                <div class="mb-6 pt-6" style="border-top: 2px solid rgba(25, 57, 72, 0.2);">
                    <h3 class="text-lg font-bold mb-2" style="color: #193948;">{{ $targetResponseLabel }}</h3>
                    <div class="p-4 rounded-lg mb-3" style="background-color: #ffffff; border: 2px solid #D6BFBF;">
                        <p class="whitespace-pre-wrap" style="color: #36454f;">{{ $complaint->{$targetResponseField} }}</p>
                    </div>
                    @if(is_array($complaint->{$targetResponseImagesField}) && count($complaint->{$targetResponseImagesField}) > 0)
                        <div class="mt-3">
                            <label class="block text-sm font-bold mb-2" style="color: #193948;">Response Attachments</label>
                            @include('blades.partials.complaint-gallery', [
                                'galleryId' => 'agent-target-response-'.$complaint->id,
                                'images' => $complaint->{$targetResponseImagesField}
                            ])
                        </div>
                    @endif
                    <p class="text-xs mt-2" style="color: #36454f;">Response date: {{ $complaint->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            @else
                <div class="mb-6 pt-6" style="border-top: 2px dashed rgba(25, 57, 72, 0.2);">
                    <p class="text-sm" style="color: #36454f; opacity: 0.7;">⏳ Waiting for {{ $complaint->target_role === 'gestionnaire' ? 'gestionnaire' : 'admin' }} response...</p>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('agent.complaints.index') }}" class="inline-block px-4 py-2 rounded font-semibold" style="background-color: #193948; color: #4FADC0; text-decoration: none;">
                    ← Back to Reports and Complaints
                </a>
            </div>
        </div>
    </div>
</x-allthepages-layout>


<x-allthepages-layout pageTitle="Complaint Details">
    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 rounded" style="background-color: #F3EBDD; color: #193948;">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948;">
            <div class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <h2 class="text-xl font-bold mb-2" style="color: #193948;">{{ $complaint->subject }}</h2>
                    <p class="text-sm mb-1" style="color: #36454f;">Target: {{ ucfirst($complaint->target_role ?? 'admin') }}</p>
                    <p class="text-sm" style="color: #36454f;">From: {{ $complaint->sender?->name ?? ucfirst($complaint->sender_role ?? 'Agent') }}</p>
                </div>
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <p class="font-semibold mb-1" style="color: #193948;">Status</p>
                    <span class="px-4 py-2 rounded text-sm font-bold" style="background-color: #193948; color: #4FADC0;">
                        {{ str_replace('_', ' ', $complaint->status) }}
                    </span>
                    <p class="text-xs mt-2" style="color: #36454f;">Submitted on {{ $complaint->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
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
                    <a href="{{ $complaint->location_link }}" target="_blank" style="color: #D6BFBF;">Open map</a>
                </div>
            @endif

            @if($complaint->images && count($complaint->images) > 0)
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2" style="color: #193948;">Attachments</label>
                    @include('blades.partials.complaint-gallery', [
                        'galleryId' => 'agent-complaint-'.$complaint->id,
                        'images' => $complaint->images
                    ])
                </div>
            @endif

            @php
                $targetResponseField = $complaint->target_role === 'gestionnaire' ? 'gestionnaire_response' : 'admin_response';
                $targetResponseImagesField = $complaint->target_role === 'gestionnaire' ? 'gestionnaire_response_images' : 'admin_response_images';
                $targetResponseLabel = $complaint->target_role === 'gestionnaire' ? 'Gestionnaire Response' : 'Admin Response';
            @endphp

            @if($complaint->target_role !== 'agent' && $complaint->{$targetResponseField})
                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-2" style="color: #193948;">{{ $targetResponseLabel }}</h3>
                    <div class="p-4 rounded-lg mb-3" style="background-color: #ffffff; border: 2px solid #D6BFBF;">
                        <p class="whitespace-pre-wrap" style="color: #36454f;">{{ $complaint->{$targetResponseField} }}</p>
                    </div>
                    @if(is_array($complaint->{$targetResponseImagesField}) && count($complaint->{$targetResponseImagesField}) > 0)
                        @include('blades.partials.complaint-gallery', [
                            'galleryId' => 'agent-target-response-'.$complaint->id,
                            'images' => $complaint->{$targetResponseImagesField}
                        ])
                    @endif
                </div>
            @endif

            @if($complaint->agent_response)
                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-2" style="color: #193948;">Your Response</h3>
                    <div class="p-4 rounded-lg mb-3" style="background-color: #ffffff; border: 2px solid #D6BFBF;">
                        <p class="whitespace-pre-wrap" style="color: #36454f;">{{ $complaint->agent_response }}</p>
                    </div>
                    @if(is_array($complaint->agent_response_images) && count($complaint->agent_response_images) > 0)
                        @include('blades.partials.complaint-gallery', [
                            'galleryId' => 'agent-response-'.$complaint->id,
                            'images' => $complaint->agent_response_images
                        ])
                    @endif
                </div>
            @endif

            @if($complaint->target_role === 'agent' && !$complaint->agent_response)
                <div class="pt-6 border-t" style="border-color: rgba(255, 227, 227, 0.2);">
                    <h3 class="text-lg font-bold mb-4" style="color: #193948;">Respond to this complaint</h3>
                    <form action="{{ route('agent.complaints.respond', $complaint->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="agent_response" class="block text-sm font-bold mb-2" style="color: #193948;">Response *</label>
                            <textarea name="agent_response" id="agent_response" rows="5" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                style="background-color: white; color: #193948;">{{ old('agent_response') }}</textarea>
                            @error('agent_response')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="agent_response_images" class="block text-sm font-bold mb-2" style="color: #193948;">Attachments (optional)</label>
                            <input type="file" name="agent_response_images[]" id="agent_response_images" multiple accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                style="background-color: white; color: #193948;">
                            @error('agent_response_images.*')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #D6BFBF; color: #193948;">
                            Send Response
                        </button>
                    </form>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('agent.complaints.index') }}" class="inline-block px-4 py-2 rounded font-semibold" style="border: 2px solid #193948; color: #193948;">
                    Back to complaints
                </a>
            </div>
        </div>
    </div>
</x-allthepages-layout>


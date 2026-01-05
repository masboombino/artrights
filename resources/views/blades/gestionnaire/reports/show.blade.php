<x-allthepages-layout pageTitle="Report #{{ $report->id }}">
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded p-4" style="background-color: #d1fae5; color: #065f46;">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <p class="font-semibold mb-1" style="color: #193948;">Subject</p>
                    <p style="color: #36454f;">{{ $report->subject }}</p>
                </div>
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <p class="font-semibold mb-1" style="color: #193948;">From</p>
                    <p style="color: #36454f;">
                        @if($report->artist)
                            {{ $report->artist->user->name ?? 'N/A' }} (Artist)
                        @elseif($report->agentProfile)
                            {{ $report->agentProfile->user->name ?? 'N/A' }} (Agent)
                        @elseif($report->sender)
                            {{ $report->sender->name ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <p class="font-semibold mb-1" style="color: #193948;">Status</p>
                    <span class="px-3 py-1 rounded text-xs" style="background-color: #193948; color: #4FADC0;">{{ $report->status }}</span>
                </div>
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <p class="font-semibold mb-1" style="color: #193948;">Location</p>
                    @if($report->location_link)
                        <a href="{{ $report->location_link }}" target="_blank" style="color: #D6BFBF;">Open Map</a>
                    @else
                        <span style="color: #36454f;">N/A</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <p class="font-semibold mb-2" style="color: #193948;">Message</p>
                <div class="p-4 rounded" style="background-color: #ffffff; border: 1px solid #193948;">
                    <p class="whitespace-pre-wrap" style="color: #36454f;">{{ $report->message }}</p>
                </div>
            </div>

            @if($report->images)
                <div class="mb-4">
                    <p class="font-semibold mb-2" style="color: #193948;">Evidence</p>
                    @include('blades.partials.complaint-gallery', [
                        'galleryId' => 'gestionnaire-report-' . $report->id,
                        'images' => $report->images
                    ])
                </div>
            @endif

            @if($report->admin_response)
                <div class="mb-4">
                    <p class="font-semibold mb-2" style="color: #193948;">Admin Response</p>
                    <div class="p-4 rounded" style="background-color: #ffffff; border: 1px solid #D6BFBF;">
                        <p class="whitespace-pre-wrap" style="color: #36454f;">{{ $report->admin_response }}</p>
                    </div>
                </div>
            @endif

            @if($report->admin_response_images)
                <div class="mb-4">
                    <p class="font-semibold mb-2" style="color: #193948;">Admin Response Images</p>
                    @include('blades.partials.complaint-gallery', [
                        'galleryId' => 'gestionnaire-admin-response-' . $report->id,
                        'images' => $report->admin_response_images
                    ])
                </div>
            @endif

            @if($report->gestionnaire_response)
                <div class="mb-4">
                    <p class="font-semibold mb-2" style="color: #193948;">Gestionnaire Response</p>
                    <div class="p-4 rounded" style="background-color: #ffffff; border: 1px solid #D6BFBF;">
                        <p class="whitespace-pre-wrap" style="color: #36454f;">{{ $report->gestionnaire_response }}</p>
                    </div>
                </div>
            @endif

            @if($report->gestionnaire_response_images)
                <div class="mb-4">
                    <p class="font-semibold mb-2" style="color: #193948;">Gestionnaire Response Images</p>
                    @include('blades.partials.complaint-gallery', [
                        'galleryId' => 'gestionnaire-self-response-' . $report->id,
                        'images' => $report->gestionnaire_response_images
                    ])
                </div>
            @endif

            @if($report->target_role === 'gestionnaire' && !$report->gestionnaire_response)
                <div class="pt-6 border-t mb-4" style="border-color: rgba(255, 227, 227, 0.2);">
                    <h3 class="text-lg font-bold mb-4" style="color: #193948;">Respond to this report</h3>
                    <form action="{{ route('gestionnaire.reports.respond', $report->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="gestionnaire_response" class="block text-sm font-bold mb-2" style="color: #193948;">Response *</label>
                            <textarea name="gestionnaire_response" id="gestionnaire_response" rows="5" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                style="background-color: white; color: #193948;">{{ old('gestionnaire_response') }}</textarea>
                            @error('gestionnaire_response')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="gestionnaire_response_images" class="block text-sm font-bold mb-2" style="color: #193948;">Attachments (optional, max 5, 10MB each)</label>
                            <input type="file" name="gestionnaire_response_images[]" id="gestionnaire_response_images" multiple accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                style="background-color: white; color: #193948;">
                            @error('gestionnaire_response_images.*')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #D6BFBF; color: #193948;">
                            Send Response
                        </button>
                    </form>
                </div>
            @endif

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('gestionnaire.reports-and-complaints.index') }}" class="rounded px-4 py-2 font-semibold" style="border: 2px solid #193948; color: #193948;">Back</a>
            </div>
        </div>
    </div>
</x-allthepages-layout>


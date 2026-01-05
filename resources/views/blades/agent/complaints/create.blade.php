<x-allthepages-layout pageTitle="Submit Complaint">
    <div class="space-y-6">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-6" style="color: #193948;">Send a Complaint</h2>

            <form action="{{ route('agent.complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                @if(!empty($targets))
                    <div>
                        <label for="target_role" class="block text-sm font-medium mb-2" style="color: #193948;">Send To *</label>
                        <select name="target_role" id="target_role" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">
                            @foreach($targets as $target)
                                <option value="{{ $target }}" @selected(old('target_role', 'gestionnaire') === $target)>
                                    {{ ucfirst(str_replace('_', ' ', $target)) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs" style="color: #36454f;">You can reach admins or gestionnaires.</p>
                        @error('target_role')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div>
                    <label for="subject" class="block text-sm font-medium mb-2" style="color: #193948;">Subject *</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                    @error('subject')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium mb-2" style="color: #193948;">Message *</label>
                    <textarea name="message" id="message" rows="6" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location_link" class="block text-sm font-medium mb-2" style="color: #193948;">Location Link (optional)</label>
                    <input type="url" name="location_link" id="location_link" value="{{ old('location_link') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://maps.google.com/...">
                    @error('location_link')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="images" class="block text-sm font-medium mb-2" style="color: #193948;">Images (optional, max 5, 10MB each)</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                    <p class="mt-1 text-xs" style="color: #36454f;">Attach any evidence if needed.</p>
                    @error('images.*')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 1rem 2rem;">
                        Submit
                    </button>
                    <a href="{{ route('agent.complaints.index') }}" class="rounded transition hover:opacity-90" style="background-color: #36454f; color: #4FADC0; padding: 1rem 2rem;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>


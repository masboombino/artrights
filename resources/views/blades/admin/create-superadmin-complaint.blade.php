<x-allthepages-layout pageTitle="Submit Complaint to Super Admin" :disableZoom="true">
    <div class="space-y-6">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-6" style="color: #D6BFBF;">Submit a Complaint/Report to Super Admin</h2>
            
            <form action="{{ route('admin.store-superadmin-complaint') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="subject" class="block text-sm font-medium mb-2" style="color: #193948;">Subject *</label>
                        <input type="text" name="subject" id="subject" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;"
                            placeholder="e.g., Request to transfer user, Issue with agency, etc.">
                        @error('subject')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium mb-2" style="color: #193948;">Message *</label>
                        <textarea name="message" id="message" rows="6" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;"
                            placeholder="Describe your issue or request in detail..."></textarea>
                        @error('message')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="images" class="block text-sm font-medium mb-2" style="color: #193948;">Images (Optional, Max 5 images, 10MB each)</label>
                        <input type="file" name="images[]" id="images" multiple accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">
                        <p class="mt-1 text-xs" style="color: #36454f; opacity: 0.8;">You can upload up to 5 images. Maximum size per image: 10MB</p>
                        @error('images.*')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem; border: none; cursor: pointer; font-weight: 600;">
                            <span style="padding: 0 0.5rem;">Submit Complaint</span>
                        </button>
                        <a href="{{ route('admin.messages.sent') }}" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem; text-decoration: none; font-weight: 600;">
                            <span style="padding: 0 0.5rem;">Cancel</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>


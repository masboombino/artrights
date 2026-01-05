<x-allthepages-layout pageTitle="Footer Settings">
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg" style="background-color: #4FADC0; color: white;">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-bold mb-6" style="color: #193948;">Footer Settings</h2>
            
            <form action="{{ route('superadmin.footer-settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Logo Upload -->
                <div class="mb-6">
                    <label for="logo" class="block text-sm font-medium mb-2" style="color: #193948;">Company Logo</label>
                    @if($settings->logo_path)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Current Logo" class="max-w-xs h-auto rounded" style="border: 2px solid #193948;">
                        </div>
                    @endif
                    <input type="file" name="logo" id="logo" accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                    @error('logo')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs" style="color: #193948; opacity: 0.7;">Upload a new image to update the logo</p>
                </div>

                <!-- Website URL -->
                <div class="mb-4">
                    <label for="website_url" class="block text-sm font-medium mb-2" style="color: #193948;">Website URL</label>
                    <input type="url" name="website_url" id="website_url" value="{{ old('website_url', $settings->website_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://example.com">
                    @error('website_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ayrade URL -->
                <div class="mb-4">
                    <label for="ayrade_url" class="block text-sm font-medium mb-2" style="color: #193948;">Ayrade URL (Link for "Ayrade" in copyright text)</label>
                    <input type="url" name="ayrade_url" id="ayrade_url" value="{{ old('ayrade_url', $settings->ayrade_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://ayrade.com">
                    @error('ayrade_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mahdid Anes URL -->
                <div class="mb-4">
                    <label for="mahdid_anes_url" class="block text-sm font-medium mb-2" style="color: #193948;">Mahdid Anes URL (Link for "Mahdid Anes" in developer text)</label>
                    <input type="url" name="mahdid_anes_url" id="mahdid_anes_url" value="{{ old('mahdid_anes_url', $settings->mahdid_anes_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://mahdidanes.com">
                    @error('mahdid_anes_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Copyright Text -->
                <div class="mb-4">
                    <label for="copyright_text" class="block text-sm font-medium mb-2" style="color: #193948;">Copyright Text</label>
                    <textarea name="copyright_text" id="copyright_text" rows="2"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">{{ old('copyright_text', $settings->copyright_text) }}</textarea>
                    @error('copyright_text')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Developer Text -->
                <div class="mb-4">
                    <label for="developer_text" class="block text-sm font-medium mb-2" style="color: #193948;">Developer Text</label>
                    <textarea name="developer_text" id="developer_text" rows="2"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">{{ old('developer_text', $settings->developer_text) }}</textarea>
                    @error('developer_text')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Support URL -->
                <div class="mb-4">
                    <label for="support_url" class="block text-sm font-medium mb-2" style="color: #193948;">Support URL</label>
                    <input type="url" name="support_url" id="support_url" value="{{ old('support_url', $settings->support_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://example.com/support">
                    @error('support_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Help URL -->
                <div class="mb-4">
                    <label for="help_url" class="block text-sm font-medium mb-2" style="color: #193948;">Help URL</label>
                    <input type="url" name="help_url" id="help_url" value="{{ old('help_url', $settings->help_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://example.com/help">
                    @error('help_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Maps URL -->
                <div class="mb-4">
                    <label for="maps_url" class="block text-sm font-medium mb-2" style="color: #193948;">Maps URL</label>
                    <input type="url" name="maps_url" id="maps_url" value="{{ old('maps_url', $settings->maps_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://maps.google.com/...">
                    @error('maps_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Social Media Links -->
                <h3 class="text-xl font-bold mb-4 mt-6" style="color: #193948;">Social Media Links</h3>

                <div class="mb-4">
                    <label for="facebook_url" class="block text-sm font-medium mb-2" style="color: #193948;">Facebook</label>
                    <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://facebook.com/...">
                    @error('facebook_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="twitter_url" class="block text-sm font-medium mb-2" style="color: #193948;">Twitter</label>
                    <input type="url" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', $settings->twitter_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://twitter.com/...">
                    @error('twitter_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="instagram_url" class="block text-sm font-medium mb-2" style="color: #193948;">Instagram</label>
                    <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://instagram.com/...">
                    @error('instagram_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="linkedin_url" class="block text-sm font-medium mb-2" style="color: #193948;">LinkedIn</label>
                    <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://linkedin.com/...">
                    @error('linkedin_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="youtube_url" class="block text-sm font-medium mb-2" style="color: #193948;">YouTube</label>
                    <input type="url" name="youtube_url" id="youtube_url" value="{{ old('youtube_url', $settings->youtube_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;" placeholder="https://youtube.com/...">
                    @error('youtube_url')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem; border: none; cursor: pointer; font-weight: 600;">
                        <span style="padding: 0 0.5rem;">Save Settings</span>
                    </button>
                    <a href="{{ route('superadmin.dashboard') }}" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem; text-decoration: none; font-weight: 600;">
                        <span style="padding: 0 0.5rem;">Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>

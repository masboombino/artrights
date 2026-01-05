<x-allthepages-layout pageTitle="Create New Device Type">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <form action="{{ route('superadmin.store-device-type') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium mb-2" style="color: #193948;">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                    @error('name')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium mb-2" style="color: #193948;">Type (Public, Commercial, Personal)</label>
                    <select name="type" id="type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                        <option value="">Select Type</option>
                        <option value="Public" @selected(old('type') === 'Public')>Public</option>
                        <option value="Commercial" @selected(old('type') === 'Commercial')>Commercial</option>
                        <option value="Personal" @selected(old('type') === 'Personal')>Personal</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="coefficient" class="block text-sm font-medium mb-2" style="color: #193948;">Coefficient</label>
                    <input type="number" name="coefficient" id="coefficient" value="{{ old('coefficient') }}" step="0.1" min="0.1" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                    @error('coefficient')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium mb-2" style="color: #193948;">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem; border: none; cursor: pointer; font-weight: 600;">
                        <span style="padding: 0 0.5rem;">Create Device Type</span>
                    </button>
                    <a href="{{ route('superadmin.manage-device-types') }}" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem; text-decoration: none; font-weight: 600;">
                        <span style="padding: 0 0.5rem;">Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>


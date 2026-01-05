<x-allthepages-layout pageTitle="Edit Category">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <form action="{{ route('superadmin.update-category', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium mb-2" style="color: #193948;">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                    @error('name')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium mb-2" style="color: #193948;">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="coefficient" class="block text-sm font-medium mb-2" style="color: #193948;">Coefficient</label>
                    <input type="number" name="coefficient" id="coefficient" value="{{ old('coefficient', $category->coefficient) }}" step="0.01" min="0" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                    @error('coefficient')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem; border: none; cursor: pointer; font-weight: 600;">
                        <span style="padding: 0 0.5rem;">Update Category</span>
                    </button>
                    <a href="{{ route('superadmin.manage-categories') }}" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem; text-decoration: none; font-weight: 600;">
                        <span style="padding: 0 0.5rem;">Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>


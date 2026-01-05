<x-allthepages-layout pageTitle="Create New Gestionnaire">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <form action="{{ route('admin.store-gestionnaire') }}" method="POST">
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
                    <label for="email" class="block text-sm font-medium mb-2" style="color: #193948;">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                    @error('email')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium mb-2" style="color: #193948;">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                    @error('phone')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium mb-2" style="color: #193948;">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                    @error('password')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: #193948;">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem; border: none; cursor: pointer; font-weight: 600;">
                        <span style="padding: 0 0.5rem;">Create Gestionnaire</span>
                    </button>
                    <a href="{{ route('admin.manage-gestionnaires') }}" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem; text-decoration: none; font-weight: 600;">
                        <span style="padding: 0 0.5rem;">Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>


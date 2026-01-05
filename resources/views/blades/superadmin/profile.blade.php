<x-allthepages-layout pageTitle="My Profile">
    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 rounded mb-4" style="background-color: #F3EBDD; color: #193948;">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-6" style="color: #193948;">Profile Information</h2>

            <form action="{{ route('superadmin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-4">
                    <div class="flex items-center gap-6">
                        @php
                            $profilePhotoUrl = $user->profile_photo_url;
                        @endphp
                        <div class="rounded-full overflow-hidden border-2 border-[#193948] flex items-center justify-center" style="background-color: #ffffff; width: 200px; height: 200px;">
                            @if($profilePhotoUrl)
                                <img src="{{ $profilePhotoUrl }}" alt="Profile photo" style="width: 100%; height: 100%; max-width: 200px; max-height: 200px; object-fit: cover;">
                            @else
                                <span style="color: #193948; font-weight: 700; font-size: 3rem;">{{ strtoupper(substr($user->name,0,1)) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label for="profile_photo" class="block text-sm font-medium mb-2" style="color: #193948;">Profile Photo</label>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   style="background-color: white; color: #193948;">
                            @error('profile_photo')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                            <p class="text-xs mt-1" style="color:#193948;">PNG أو JPG بحد أقصى 10 ميغا.</p>
                        </div>
                    </div>
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2" style="color: #193948;">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">
                        @error('name')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium mb-2" style="color: #193948;">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">
                        @error('email')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium mb-2" style="color: #193948;">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">
                        @error('phone')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-2" style="color: #193948;">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">
                        @error('password')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: #193948;">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 1rem 2rem;">
                            <span style="padding: 0 0.5rem;">Update Profile</span>
                        </button>
                        <a href="{{ route('superadmin.dashboard') }}" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 1rem 2rem;">
                            <span style="padding: 0 0.5rem;">Cancel</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>

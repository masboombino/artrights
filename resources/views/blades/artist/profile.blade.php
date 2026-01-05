<x-allthepages-layout pageTitle="My Profile">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="page-container">
            <h2 style="color: #193948; font-size: 1.75rem; font-weight: 700; margin-bottom: 1.5rem;">Profile Information</h2>
            
            <form action="{{ route('artist.update-profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div style="display: flex; align-items: center; gap: 20px; margin: 5px; padding: 5px; flex-wrap: wrap;">
                    @php
                        $profilePhotoUrl = $artist->user->profile_photo_url;
                    @endphp
                    <div style="width: 128px; height: 128px; border-radius: 50%; overflow: hidden; border: 4px solid #193948; display: flex; align-items: center; justify-content: center; background-color: white;">
                        @if($profilePhotoUrl)
                            <img src="{{ $profilePhotoUrl }}" alt="Profile photo" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="color: #193948; font-weight: 700; font-size: 3rem;">{{ strtoupper(substr($artist->user->name,0,1)) }}</span>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <label for="profile_photo" class="form-label">Profile Photo</label>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="form-input">
                        @error('profile_photo')
                            <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                        <p style="color: #193948; font-size: 0.85rem; margin-top: 0.25rem;">PNG or JPG (Max: 10MB)</p>
                    </div>
                </div>
            
                <div style="margin: 5px; padding: 5px;">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" value="{{ $artist->user->name }}" required class="form-input">
                    @error('name')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ $artist->user->email }}" required class="form-input">
                    @error('email')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ $artist->user->phone ?? '' }}" class="form-input">
                    @error('phone')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label for="stage_name" class="form-label">Stage Name</label>
                    <input type="text" name="stage_name" id="stage_name" value="{{ $artist->stage_name ?? '' }}" class="form-input">
                    @error('stage_name')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" id="address" value="{{ $artist->address ?? '' }}" class="form-input">
                    @error('address')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Agency Information Card -->
                <div style="background-color: rgba(214, 191, 191, 0.1); border: 2px solid #193948; border-radius: 10px; padding: 1.5rem; margin: 1rem 0;">
                    <h3 style="color: #193948; font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center;">
                        🏢 Agency Information
                    </h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">Agency Name</label>
                            <div style="background-color: white; border: 1px solid #193948; border-radius: 5px; padding: 0.75rem; color: #193948; font-weight: 500;">
                                {{ $artist->agency ? $artist->agency->agency_name : 'Not Assigned' }}
                            </div>
                        </div>
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">Wilaya</label>
                            <div style="background-color: white; border: 1px solid #193948; border-radius: 5px; padding: 0.75rem; color: #193948; font-weight: 500;">
                                {{ $artist->agency ? $artist->agency->wilaya : 'Not Assigned' }}
                            </div>
                        </div>
                    </div>
                    <p style="color: #193948; font-size: 0.8rem; margin-top: 1rem; font-style: italic;">Agency information cannot be changed by the user</p>
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label for="birth_place" class="form-label">Birth Place</label>
                    <input type="text" name="birth_place" id="birth_place" value="{{ $artist->birth_place ?? '' }}" class="form-input">
                    @error('birth_place')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label for="birth_date" class="form-label">Birth Date</label>
                    <input type="date" name="birth_date" id="birth_date" value="{{ $artist->birth_date ?? '' }}" class="form-input">
                    @error('birth_date')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" id="password" class="form-input">
                    @error('password')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input">
                </div>

                <div style="display: flex; gap: 10px; margin-top: 1.5rem; padding: 5px; flex-wrap: wrap;">
                    <button type="submit" class="primary-button">
                        Update Profile
                    </button>
                    <a href="{{ route('artist.dashboard') }}" class="secondary-button">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>

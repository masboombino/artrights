<x-guest-layout>
    <!-- Platform Description -->
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold mb-4" style="color: #36454f; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Register New Artist Account</h2>
        <p class="text-sm font-semibold" style="color: #36454f; opacity: 0.9;">Fill in the form below to submit your registration for review</p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- First Name -->
        <div class="mb-4">
            <x-input-label for="first_name" :value="__('First Name')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="first_name" class="block w-full px-4 py-3" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mb-4">
            <x-input-label for="last_name" :value="__('Last Name')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="last_name" class="block w-full px-4 py-3" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Stage Name -->
        <div class="mb-4">
            <x-input-label for="stage_name" :value="__('Stage Name (Optional)')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="stage_name" class="block w-full px-4 py-3" type="text" name="stage_name" :value="old('stage_name')" autocomplete="nickname" style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <x-input-error :messages="$errors->get('stage_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="email" class="block w-full px-4 py-3" type="email" name="email" :value="old('email')" required autocomplete="username" style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <x-input-label for="phone" :value="__('Phone')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="phone" class="block w-full px-4 py-3" type="text" name="phone" :value="old('phone')" required autocomplete="tel" style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Birth Date -->
        <div class="mb-4">
            <x-input-label for="birth_date" :value="__('Birth Date')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="birth_date" class="block w-full px-4 py-3" type="date" name="birth_date" :value="old('birth_date')" required style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div>

        <!-- Birth Place -->
        <div class="mb-4">
            <x-input-label for="birth_place" :value="__('Birth Place')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="birth_place" class="block w-full px-4 py-3" type="text" name="birth_place" :value="old('birth_place')" required style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <x-input-error :messages="$errors->get('birth_place')" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="mb-4">
            <x-input-label for="address" :value="__('Current Address')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="address" class="block w-full px-4 py-3" type="text" name="address" :value="old('address')" required style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Agency -->
        <div class="mb-4">
            <x-input-label for="agency_id" :value="__('Agency')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <select id="agency_id" name="agency_id" required class="block w-full px-4 py-3 rounded-lg shadow-sm" style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;">
                <option value=""> Choose your closest agency  </option>
                @foreach(\App\Models\Agency::orderBy('wilaya')->orderBy('agency_name')->get() as $agency)
                    <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>{{ $agency->agency_name }} - {{ $agency->wilaya }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('agency_id')" class="mt-2" />
        </div>

        <!-- Identity Document -->
        <div class="mb-4">
            <x-input-label for="identity_document" :value="__('Identity Document (Artist Card, Certificate, etc.)')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <input id="identity_document" class="block w-full px-4 py-3 rounded-lg shadow-sm" type="file" name="identity_document" accept="image/*,.pdf" required style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <p class="mt-2 text-xs font-medium" style="color: #36454f; opacity: 0.8;">Accepted formats: JPG, PNG, PDF (Max: 5MB)</p>
            <x-input-error :messages="$errors->get('identity_document')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="password" class="block w-full px-4 py-3"
                            type="password"
                            name="password"
                            required autocomplete="new-password" style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="password_confirmation" class="block w-full px-4 py-3"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-8">
            <a class="underline text-sm font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition hover:opacity-80" href="{{ route('login') }}" style="color: #193948;">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 px-8 py-3 font-bold text-base rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" style="background-color: #193948 !important; color: #4FADC0 !important; border: none !important;">
                {{ __('Submit for Review') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

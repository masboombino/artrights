<x-guest-layout>
    <!-- Welcome Section -->
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold mb-3" style="color: #36454f; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Welcome Back</h2>
        <p class="text-sm font-semibold" style="color: #36454f; opacity: 0.9;">Sign in to your ArtRights account</p>
    </div>

    <!-- Session Status -->
    @if(session('status'))
        <div class="mb-6 p-4 rounded-lg border-4 shadow-lg" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-color: #f59e0b; border-width: 3px;">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6" style="color: #f59e0b;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-lg font-bold mb-1" style="color: #92400e;">Account Status</h3>
                    <p class="text-sm font-semibold" style="color: #78350f;">{{ session('status') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />
            <x-text-input id="email" class="block w-full px-4 py-3" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />
            @if($errors->has('email'))
                <div class="mt-2 p-3 rounded-lg border-2" style="background-color: #fee2e2; border-color: #dc2626;">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color: #dc2626;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-2 text-sm font-semibold" style="color: #991b1b;">{{ $errors->first('email') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" style="color: #36454f; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;" />

            <x-text-input id="password" class="block w-full px-4 py-3"
                            type="password"
                            name="password"
                            required autocomplete="current-password" style="color: #36454f !important; background-color: #ffffff !important; border: 2px solid #193948 !important; border-radius: 8px !important; font-size: 1rem;" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 shadow-sm focus:ring-indigo-500 w-4 h-4" name="remember" style="border-color: #193948 !important; accent-color: #193948 !important;">
                <span class="ms-2 text-sm font-semibold" style="color: #36454f;">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mb-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition hover:opacity-80" href="{{ route('password.request') }}" style="color: #193948;">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 px-8 py-3 font-bold text-base rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" style="background-color: #193948 !important; color: #4FADC0 !important; border: none !important;">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center pt-6 border-t-2" style="border-color: rgba(255, 227, 227, 0.2);">
        <p class="text-sm font-semibold" style="color: #36454f; opacity: 0.9;">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-bold underline hover:opacity-80 transition" style="color: #193948;">Register here</a>
        </p>
    </div>
</x-guest-layout>

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 responsive-nav-container">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings & Notifications -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                @php
                    $user = Auth::user();
                    $unreadNotifications = $user?->notifications()->where('is_read', false)->count() ?? 0;
                    $notificationsRoute = null;
                    if ($user) {
                        if ($user->hasRole('super_admin')) {
                            $notificationsRoute = route('superadmin.notifications');
                        } elseif ($user->hasRole('admin')) {
                            $notificationsRoute = route('admin.notifications');
                        } elseif ($user->hasRole('gestionnaire')) {
                            $notificationsRoute = route('gestionnaire.notifications');
                        } elseif ($user->hasRole('artist')) {
                            $notificationsRoute = route('artist.notifications');
                        } elseif ($user->hasRole('agent')) {
                            $notificationsRoute = route('agent.notifications');
                        }
                    }
                    $showProfileButton = $user && !$user->hasAnyRole(['super_admin', 'admin']);
                    $profileInitials = null;
                    if ($showProfileButton && $user?->name) {
                        $parts = preg_split('/\s+/', trim($user->name));
                        $initials = collect($parts)->filter()->map(function ($part) {
                            return mb_substr($part, 0, 1);
                        })->take(2)->implode('');
                        $profileInitials = strtoupper($initials ?: 'U');
                    }
                    $profilePhotoUrl = $showProfileButton ? $user?->profile_photo_url : null;
                @endphp
                <span class="font-medium text-sm text-gray-700 dark:text-gray-200">
                    {{ $user->name }}
                </span>
                @if($showProfileButton)
                    @php
                        $profileRoute = null;
                        if ($user->hasRole('artist')) {
                            $profileRoute = 'artist.profile';
                        } elseif ($user->hasRole('agent') && Route::has('agent.profile')) {
                            $profileRoute = 'agent.profile';
                        } elseif ($user->hasRole('gestionnaire') && Route::has('gestionnaire.profile')) {
                            $profileRoute = 'gestionnaire.profile';
                        } elseif (Route::has('profile.edit')) {
                            $profileRoute = 'profile.edit';
                        }
                    @endphp
                    <div>
                        <a href="{{ $profileRoute ? route($profileRoute) : '#' }}"
                           class="inline-flex items-center justify-center w-12 h-12 rounded-full border border-gray-300 dark:border-gray-500 bg-gray-100 dark:bg-gray-700 transition hover:scale-105 overflow-hidden"
                           aria-label="Profile">
                            @if($profilePhotoUrl)
                                <img src="{{ $profilePhotoUrl }}" alt="Profile photo" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $profileInitials }}</span>
                            @endif
                        </a>
                    </div>
                @endif
                @if($notificationsRoute)
                    <a href="{{ $notificationsRoute }}"
                       class="relative inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 transition hover:scale-105"
                       aria-label="Notifications">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9a6 6 0 1 0-12 0v.75a8.967 8.967 0 0 1-2.311 6.022c1.733.64 3.56 1.085 5.455 1.31m5.713 0a24.255 24.255 0 0 1-5.713 0m5.713 0a3 3 0 1 1-5.713 0" />
                        </svg>
                        @if($unreadNotifications > 0)
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold leading-none text-white rounded-full"
                                  style="background-color:#F04D4D;">
                                {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                            </span>
                        @endif
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 transition hover:scale-105 text-lg"
                            aria-label="Logout">
                        ➜]
                    </button>
                </form>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if($showProfileButton && $profileRoute)
                <x-responsive-nav-link :href="route($profileRoute)" :active="request()->is(str_replace('.', '/', $profileRoute))">
                    <span class="inline-flex items-center gap-2">
                        @if($profilePhotoUrl)
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full overflow-hidden border border-gray-300 dark:border-gray-500">
                                <img src="{{ $profilePhotoUrl }}" alt="Profile photo" class="w-full h-full object-cover">
                            </span>
                        @else
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-gray-300 dark:border-gray-500 bg-white/70 text-sm font-bold">
                                {{ $profileInitials }}
                            </span>
                        @endif
                        Profile
                    </span>
                </x-responsive-nav-link>
            @endif
            @if($notificationsRoute)
                <x-responsive-nav-link :href="$notificationsRoute" :active="request()->is('*notifications')">
                    Notifications
                    @if($unreadNotifications > 0)
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold" style="background-color:#F04D4D; color:white;">
                            {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                        </span>
                    @endif
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        ➜] {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Navigation Responsive Styles */
        @media (max-width: 1024px) {
            .responsive-nav-container {
                height: 14rem !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                padding: 1rem !important;
            }

            .responsive-nav-container > div:first-child {
                width: 100% !important;
                margin-bottom: 1rem !important;
            }

            .responsive-nav-container > div:last-child {
                width: 100% !important;
                justify-content: center !important;
            }
        }

        @media (max-width: 768px) {
            .responsive-nav-container {
                height: 12rem !important;
                padding: 0.75rem !important;
            }

            .username-badge {
                font-size: 0.9rem !important;
                padding: 0.25rem 0.6rem 0.25rem 0.7rem !important;
                gap: 0.4rem !important;
                height: 42px !important;
            }

            .username-profile-photo {
                width: 38px !important;
                height: 38px !important;
                font-size: 1.3rem !important;
                margin: -1px 0 -1px 0 !important;
            }

            .notification-icon {
                width: 38px !important;
                height: 38px !important;
            }
        }

        @media (max-width: 640px) {
            .responsive-nav-container {
                height: 10rem !important;
                padding: 0.5rem !important;
            }

            .username-badge {
                font-size: 0.85rem !important;
                padding: 0.2rem 0.5rem 0.2rem 0.6rem !important;
                gap: 0.35rem !important;
                height: 38px !important;
            }

            .username-profile-photo {
                width: 35px !important;
                height: 35px !important;
                font-size: 1.2rem !important;
            }

            .notification-icon {
                width: 35px !important;
                height: 35px !important;
            }
        }

        @media (max-width: 480px) {
            .responsive-nav-container {
                height: 8rem !important;
                padding: 0.4rem !important;
            }

            .username-badge {
                font-size: 0.8rem !important;
                padding: 0.15rem 0.4rem 0.15rem 0.5rem !important;
                gap: 0.3rem !important;
                height: 34px !important;
            }

            .username-profile-photo {
                width: 32px !important;
                height: 32px !important;
                font-size: 1.1rem !important;
            }

            .notification-icon {
                width: 32px !important;
                height: 32px !important;
            }
        }
    </style>
</nav>

<!DOCTYPE html>
<html lang="{{ $lang ?? 'en' }}" dir="{{ ($lang ?? 'en') == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Help Guide - ArtRights</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('icons/thefavicon.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            :root {
                --primary-dark: #193948;
                --primary-light: #2a4a5a;
                --accent-teal: #4FADC0;
                --accent-cream: #D6BFBF;
                --bg-cream: #F3EBDD;
                --bg-dark: #36454f;
                --text-light: #F3EBDD;
            }

            html {
                scroll-behavior: smooth;
                overflow-x: hidden;
            }

            body {
                font-family: 'Figtree', 'Cairo', sans-serif;
                overflow-x: hidden;
                background-color: var(--bg-dark);
                color: var(--text-light);
                line-height: 1.6;
            }

            @if(($lang ?? 'en') == 'ar')
            body {
                font-family: 'Cairo', 'Figtree', sans-serif;
            }
            @endif

            /* Animated Background */
            .animated-bg {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                background: linear-gradient(135deg, #193948 0%, #2a4a5a 50%, #36454f 100%);
                overflow: hidden;
            }

            .animated-bg::before {
                content: '';
                position: absolute;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(79, 173, 192, 0.1) 0%, transparent 70%);
                animation: rotate 20s linear infinite;
                top: -50%;
                left: -50%;
            }

            .animated-bg::after {
                content: '';
                position: absolute;
                width: 150%;
                height: 150%;
                background: radial-gradient(circle, rgba(214, 191, 191, 0.08) 0%, transparent 60%);
                animation: rotate 15s linear infinite reverse;
                top: -25%;
                right: -25%;
            }

            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            /* Floating Particles - Circles */
            .particle {
                position: absolute;
                background: rgba(243, 235, 221, 0.1);
                border-radius: 50%;
                animation: float-particle 8s infinite ease-in-out;
                pointer-events: none;
            }

            @keyframes float-particle {
                0%, 100% {
                    transform: translate(0, 0) scale(1);
                    opacity: 0.2;
                }
                50% {
                    transform: translate(100px, -100px) scale(1.5);
                    opacity: 0.4;
                }
            }

            .hero {
                padding: 4rem 2rem 2rem;
                text-align: center;
                position: relative;
                z-index: 10;
            }

            .hero-content {
                max-width: 1200px;
                margin: 0 auto;
                animation: fadeInUp 1s ease;
                position: relative;
                z-index: 10;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .site-header {
                margin-bottom: 2rem;
            }

            .site-title {
                font-size: 4rem;
                font-weight: 700;
                color: #F3EBDD;
                font-family: 'Pacifico', cursive;
                text-shadow: 0 4px 8px rgba(0,0,0,0.3), 0 0 20px rgba(243, 235, 221, 0.5);
                text-decoration: none;
                letter-spacing: 2px;
                display: inline-block;
                transition: all 0.3s ease;
            }

            .site-title:hover {
                transform: scale(1.05);
            }

            .section-container {
                background-color: #F3EBDD;
                border: 4px solid #193948;
                border-radius: 16px;
                padding: 3rem;
                margin: 2rem auto;
                max-width: 1200px;
                position: relative;
                z-index: 10;
            }

            .hero-subtitle {
                font-size: 2rem;
                color: #193948;
                margin-bottom: 1rem;
                font-weight: 700;
            }

            .hero-description {
                font-size: 1.1rem;
                color: #193948;
                line-height: 1.8;
                margin-bottom: 2rem;
                opacity: 0.9;
            }

            .roles-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 2rem;
                margin-top: 3rem;
            }

            .role-card {
                background: #F3EBDD;
                padding: 2rem;
                border-radius: 12px;
                border: 3px solid #193948;
                transition: all 0.3s ease;
                text-align: left;
                margin: 5px;
            }

            @if(($lang ?? 'en') == 'ar')
            .role-card {
                text-align: right;
            }
            @endif

            .role-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 16px rgba(25, 57, 72, 0.2);
                border-color: #4FADC0;
            }

            .role-header {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin-bottom: 1.5rem;
                padding-bottom: 1rem;
                border-bottom: 2px solid #193948;
            }

            .role-icon {
                font-size: 3rem;
            }

            .role-title {
                font-size: 1.8rem;
                font-weight: 700;
                color: #193948;
                flex: 1;
            }

            .role-description {
                color: #193948;
                line-height: 1.7;
                font-size: 1rem;
                margin-bottom: 1.5rem;
                font-weight: 500;
            }

            .permissions-title {
                font-size: 1.2rem;
                font-weight: 700;
                color: #4FADC0;
                margin-bottom: 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 2px solid #4FADC0;
            }

            .permissions-list {
                list-style: none;
                padding: 0;
            }

            .permission-item {
                color: #193948;
                padding: 0.75rem;
                margin-bottom: 0.5rem;
                background: rgba(79, 173, 192, 0.1);
                border-radius: 8px;
                border-left: 4px solid #4FADC0;
                font-size: 0.95rem;
                transition: all 0.3s ease;
            }

            .permission-item:hover {
                background: rgba(79, 173, 192, 0.2);
                transform: translateX(5px);
            }

            .permission-item::before {
                content: "✓ ";
                color: #4FADC0;
                font-weight: 700;
                margin-right: 0.5rem;
            }

            @if(($lang ?? 'en') == 'ar')
            .permission-item {
                border-left: none;
                border-right: 4px solid #4FADC0;
            }

            .permission-item:hover {
                transform: translateX(-5px);
            }

            .permission-item::before {
                margin-right: 0;
                margin-left: 0.5rem;
            }
            @endif

            .no-permissions {
                color: #193948;
                font-style: italic;
                text-align: center;
                padding: 1rem;
                background: rgba(214, 191, 191, 0.2);
                border-radius: 8px;
            }

            .btn-group {
                display: flex;
                gap: 1.5rem;
                justify-content: center;
                flex-wrap: wrap;
                margin-top: 2rem;
            }

            .btn-primary {
                background: #4FADC0;
                color: #193948;
                padding: 0.75rem 2rem;
                border-radius: 1rem;
                text-decoration: none;
                font-weight: 700;
                transition: all 0.3s ease;
                border: none;
                display: inline-block;
                box-shadow: 0 4px 6px rgba(79, 173, 192, 0.3);
            }

            .btn-primary:hover {
                background: #D6BFBF;
                color: #193948;
                transform: translateY(-2px) scale(1.05);
                box-shadow: 0 6px 12px rgba(214, 191, 191, 0.4);
            }

            .btn-secondary {
                background: #D6BFBF;
                color: #193948;
                padding: 0.75rem 2rem;
                border-radius: 1rem;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                border: none;
                display: inline-block;
                box-shadow: 0 4px 6px rgba(214, 191, 191, 0.3);
            }

            .btn-secondary:hover {
                background: #4FADC0;
                color: #193948;
                transform: translateY(-2px) scale(1.05);
                box-shadow: 0 6px 12px rgba(79, 173, 192, 0.4);
            }

            .nav-link-btn {
                background: #D6BFBF;
                color: #193948;
                text-decoration: none;
                font-weight: 600;
                padding: 0.5rem 1rem;
                border-radius: 1rem;
                transition: all 0.3s ease;
                display: inline-block;
                margin: 0;
                box-shadow: 0 2px 4px rgba(214, 191, 191, 0.3);
                white-space: nowrap;
            }

            .nav-link-btn:hover {
                background: #4FADC0;
                color: #193948;
                transform: translateY(-2px) scale(1.05);
                box-shadow: 0 4px 8px rgba(79, 173, 192, 0.4);
            }

            .nav-btn-primary {
                background: #4FADC0;
                color: #193948;
                text-decoration: none;
                font-weight: 600;
                padding: 0.5rem 1rem;
                border-radius: 1rem;
                transition: all 0.3s ease;
                display: inline-block;
                margin: 0;
                box-shadow: 0 2px 4px rgba(79, 173, 192, 0.3);
                white-space: nowrap;
            }

            .nav-btn-primary:hover {
                background: #D6BFBF;
                color: #193948;
                transform: translateY(-2px) scale(1.05);
                box-shadow: 0 4px 8px rgba(214, 191, 191, 0.4);
            }

            .lang-switcher {
                background: #193948;
                color: #F3EBDD;
                padding: 0.5rem 1rem;
                border-radius: 1rem;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-block;
                margin: 5px;
                font-size: 0.9rem;
            }

            .lang-switcher:hover {
                background: #4FADC0;
                color: #193948;
                transform: translateY(-2px);
            }

            /* Footer */
            .main-footer {
                background: linear-gradient(180deg, transparent 0%, rgba(25, 57, 72, 0.5) 100%);
                padding: 4rem 2rem 2rem;
                border-top: 3px solid rgba(243, 235, 221, 0.2);
                position: relative;
                z-index: 10;
            }

            .footer-content {
                max-width: 1400px;
                margin: 0 auto;
            }

            .footer-top {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: center;
                gap: 2rem;
                margin-bottom: 2rem;
            }

            .footer-logo-section {
                display: flex;
                align-items: center;
                gap: 0;
            }

            .footer-logo {
                width: 90px;
                height: 90px;
                object-fit: contain;
                cursor: pointer;
                transition: all 0.3s ease;
                border-radius: 0.5rem;
                padding: 0.5rem;
                background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%);
                box-shadow: 0 0 15px rgba(243, 235, 221, 0.4), 0 0 30px rgba(214, 191, 191, 0.25), inset 0 0 10px rgba(243, 235, 221, 0.2);
                animation: glow-pulse 3s ease-in-out infinite;
            }

            @keyframes glow-pulse {
                0%, 100% {
                    box-shadow: 0 0 15px rgba(243, 235, 221, 0.4), 0 0 30px rgba(214, 191, 191, 0.25), inset 0 0 10px rgba(243, 235, 221, 0.2);
                }
                50% {
                    box-shadow: 0 0 25px rgba(243, 235, 221, 0.6), 0 0 50px rgba(214, 191, 191, 0.4), inset 0 0 15px rgba(243, 235, 221, 0.3);
                }
            }

            .footer-logo:hover {
                transform: scale(1.1);
            }

            .footer-text-section {
                flex: 1;
                text-align: left;
                padding-left: 100px;
            }

            .footer-text {
                font-size: 1rem;
                color: rgba(243, 235, 221, 0.9);
                margin: 0.25rem 0;
            }

            .footer-actions {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 0.75rem;
            }

            .footer-links {
                display: flex;
                gap: 0.4rem;
                justify-content: flex-end;
                flex-wrap: wrap;
            }

            .footer-link {
                color: var(--text-light);
                text-decoration: none;
                padding: 0.5rem 0.75rem;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
                font-weight: 500;
                font-size: 0.9rem;
            }

            .footer-link:hover {
                background-color: rgba(25, 57, 72, 0.5);
                transform: translateY(-2px);
            }

            .footer-social {
                display: flex;
                gap: 0.75rem;
                justify-content: flex-end;
            }

            .footer-social-link {
                width: 45px;
                height: 45px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: rgba(25, 57, 72, 0.5);
                color: var(--text-light);
                border-radius: 50%;
                text-decoration: none;
                transition: all 0.3s ease;
                font-size: 1.3rem;
            }

            .footer-social-link:hover {
                background-color: var(--accent-teal);
                transform: scale(1.1) rotate(5deg);
            }

            .site-logo-text {
                font-family: 'Pacifico', cursive;
                font-size: 2rem;
                color: #193948;
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .site-logo-text:hover {
                color: #D6BFBF;
                transform: scale(1.05);
            }

            .intro-section {
                background: rgba(79, 173, 192, 0.1);
                padding: 2rem;
                border-radius: 12px;
                margin-bottom: 2rem;
                border: 2px solid #4FADC0;
            }

            .intro-text {
                color: #193948;
                font-size: 1.1rem;
                line-height: 1.8;
                text-align: center;
            }

            @media (max-width: 768px) {
                .site-title {
                    font-size: 3rem;
                }

                .hero-subtitle {
                    font-size: 1.5rem;
                }

                .section-container {
                    padding: 1.5rem;
                }

                .btn-group {
                    flex-direction: column;
                    align-items: center;
                }

                .site-logo-text {
                    font-size: 1.5rem;
                }

                .roles-grid {
                    grid-template-columns: 1fr;
                }

                .footer-top {
                    flex-direction: column;
                    text-align: center;
                }

                .footer-text-section {
                    padding-left: 0;
                    text-align: center;
                }

                .footer-actions {
                    align-items: center;
                }
            }

            @media (max-width: 640px) {
                .site-title {
                    font-size: 2rem;
                }

                .role-header {
                    flex-direction: column;
                    text-align: center;
                }

                header {
                    padding: 0.5rem 0.75rem !important;
                    min-height: auto !important;
                }

                header > div > div {
                    flex-wrap: nowrap !important;
                    gap: 0.5rem !important;
                }

                .site-logo-text {
                    font-size: 1.25rem !important;
                }

                .nav-link-btn,
                .nav-btn-primary {
                    padding: 0.35rem 0.6rem !important;
                    font-size: 0.8rem !important;
                }

                header a img {
                    width: 28px !important;
                }
            }

            @media (max-width: 768px) {
                header {
                    padding: 0.75rem 1rem !important;
                }

                .nav-link-btn,
                .nav-btn-primary {
                    padding: 0.4rem 0.75rem;
                    font-size: 0.875rem;
                }
            }
        </style>
    </head>

    <body>
        <div class="animated-bg"></div>
        <div class="min-h-screen" style="position: relative; z-index: 10;">

            <!-- Header -->
            <header style="background-color: #F3EBDD; border-bottom: 4px solid #193948; padding: 1rem 2rem; position: relative; min-height: 85px; display: flex; align-items: center; z-index: 100;">
                <div class="max-w-7xl mx-auto" style="width: 100%;">
                    <div class="flex justify-between items-center gap-4" style="height: 100%;">

                        <!-- Site Name with Icon -->
                        <div class="flex items-center gap-3">
                            <a href="/" style="display: flex; align-items: stretch; text-decoration: none; position: relative; margin: -1rem 0; height: calc(85px); align-self: flex-end;">
                                <img src="{{ asset('icons/logo.png') }}" alt="ArtRights Logo" style="width: 35px; height: 100%; object-fit: contain;">
                            </a>
                            <a href="/" class="site-logo-text">
                                ArtRights
                            </a>
                        </div>

                        <!-- Navigation -->
                        <div class="flex items-center gap-2" style="flex-wrap: nowrap; flex-shrink: 0;">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="nav-link-btn">Dashboard</a>
                            @else
                                <a href="{{ route('support') }}" class="nav-link-btn">Support</a>
                                <a href="{{ route('login') }}" class="nav-link-btn">Log In</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="nav-btn-primary">Sign Up</a>
                                @endif
                            @endauth
                        </div>

                    </div>
                </div>
            </header>

            <!-- HERO SECTION -->
            <main>
                <section class="hero">
                    <div class="hero-content">
                        <div class="site-header">
                            <a href="/" class="site-title">ArtRights</a>
                            <p class="site-subtitle" style="margin-top: 1rem; font-size: 0.9rem; font-weight: 500; color: #F3EBDD; text-shadow: 0 2px 4px rgba(0,0,0,0.2); opacity: 0.95;">
                                @if(($lang ?? 'en') == 'ar')
                                    - منصة حماية حقوق الفنانين -
                                @else
                                    - A platform that protects artists' creations and preserves their intellectual rights -
                                @endif
                            </p>
                        </div>

                        <div class="section-container">
                            <h1 class="hero-subtitle">{{ ($lang ?? 'en') == 'ar' ? 'دليل المساعدة' : 'Help Guide' }}</h1>
                            <p class="hero-description">
                                @if(($lang ?? 'en') == 'ar')
                                    تعرف على الأدوار المختلفة في المنصة والصلاحيات المتاحة لكل دور
                                @else
                                    Learn about the different roles in the platform and the permissions available for each role
                                @endif
                            </p>

                            @if($userRole && ($userRole->name == 'artist' || $userRole->name == 'agent'))
                            <div class="intro-section" style="background: rgba(79, 173, 192, 0.15); border-color: #4FADC0;">
                                <p class="intro-text" style="font-weight: 600; color: #193948;">
                                    @if(($lang ?? 'en') == 'ar')
                                        📱 لديك تطبيق خاص يمكنك استعماله وهو أسهل من الموقع بكثير!
                                    @else
                                        📱 You have a dedicated mobile application that you can use, which is much easier than the website!
                                    @endif
                                </p>
                            </div>
                            @endif

                            <div class="intro-section">
                                <p class="intro-text">
                                    @if(($lang ?? 'en') == 'ar')
                                        منصة ArtRights تحتوي على عدة أدوار مختلفة، كل دور له صلاحيات ومسؤوليات محددة. 
                                        في هذا الدليل يمكنك التعرف على ما يمكن لكل دور القيام به في المنصة.
                                    @else
                                        The ArtRights platform contains several different roles, each with specific permissions and responsibilities.
                                        In this guide, you can learn about what each role can do on the platform.
                                    @endif
                                </p>
                            </div>

                            @if($currentRoleDetails && $userRole)
                            <!-- Current User's Role Section -->
                            <div style="margin-bottom: 3rem;">
                                <h2 class="hero-subtitle" style="font-size: 1.8rem; margin-bottom: 2rem; text-align: {{ ($lang ?? 'en') == 'ar' ? 'right' : 'left' }};">
                                    {{ ($lang ?? 'en') == 'ar' ? 'دورك الحالي' : 'Your Current Role' }}
                                </h2>
                                
                                <div class="role-card" style="border-color: #4FADC0; border-width: 4px;">
                                    <div class="role-header">
                                        <div class="role-icon">
                                            @if($userRole->name == 'super_admin')
                                                👑
                                            @elseif($userRole->name == 'admin')
                                                🛡️
                                            @elseif($userRole->name == 'gestionnaire')
                                                📋
                                            @elseif($userRole->name == 'agent')
                                                👮
                                            @elseif($userRole->name == 'artist')
                                                🎨
                                            @else
                                                👤
                                            @endif
                                        </div>
                                        <h2 class="role-title">
                                            @foreach($rolesData as $role)
                                                @if($role['key'] == $userRole->name)
                                                    {{ $role['name'] }}
                                                @endif
                                            @endforeach
                                        </h2>
                                    </div>
                                    
                                    <p class="role-description">
                                        @foreach($rolesData as $role)
                                            @if($role['key'] == $userRole->name)
                                                {{ $role['description'] }}
                                            @endif
                                        @endforeach
                                    </p>

                                    <div style="margin-top: 2rem;">
                                        <h3 class="permissions-title">{{ ($lang ?? 'en') == 'ar' ? 'حقوقك:' : 'Your Rights:' }}</h3>
                                        <ul class="permissions-list">
                                            @foreach($currentRoleDetails['rights'] as $right)
                                                <li class="permission-item">{{ $right }}</li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div style="margin-top: 2rem;">
                                        <h3 class="permissions-title" style="color: #D6BFBF;">{{ ($lang ?? 'en') == 'ar' ? 'واجباتك:' : 'Your Responsibilities:' }}</h3>
                                        <ul class="permissions-list">
                                            @foreach($currentRoleDetails['responsibilities'] as $responsibility)
                                                <li class="permission-item" style="border-color: #D6BFBF;">{{ $responsibility }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- All Roles Section -->
                            <h2 class="hero-subtitle" style="font-size: 1.8rem; margin-bottom: 2rem; margin-top: 3rem; text-align: {{ ($lang ?? 'en') == 'ar' ? 'right' : 'left' }};">
                                {{ ($lang ?? 'en') == 'ar' ? 'جميع الأدوار في المنصة' : 'All Platform Roles' }}
                            </h2>

                            <div class="roles-grid">
                                @foreach($rolesData as $role)
                                <div class="role-card">
                                    <div class="role-header">
                                        <div class="role-icon">
                                            @if($role['key'] == 'super_admin')
                                                👑
                                            @elseif($role['key'] == 'admin')
                                                🛡️
                                            @elseif($role['key'] == 'gestionnaire')
                                                📋
                                            @elseif($role['key'] == 'agent')
                                                👮
                                            @elseif($role['key'] == 'artist')
                                                🎨
                                            @else
                                                👤
                                            @endif
                                        </div>
                                        <h2 class="role-title">{{ $role['name'] }}</h2>
                                    </div>
                                    
                                    <p class="role-description">{{ $role['description'] }}</p>
                                    
                                    @if(count($role['permissions']) > 0)
                                        <h3 class="permissions-title">{{ ($lang ?? 'en') == 'ar' ? 'الصلاحيات المتاحة:' : 'Available Permissions:' }}</h3>
                                        <ul class="permissions-list">
                                            @foreach($role['permissions'] as $permission)
                                                <li class="permission-item">{{ $permission['name'] }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="no-permissions">{{ ($lang ?? 'en') == 'ar' ? 'لا توجد صلاحيات خاصة لهذا الدور' : 'No special permissions for this role' }}</p>
                                    @endif
                                </div>
                                @endforeach
                            </div>

                            <div class="btn-group">
                                <a href="{{ route('support') }}{{ ($lang ?? 'en') == 'ar' ? '?lang=ar' : '' }}" class="btn-primary">
                                    {{ ($lang ?? 'en') == 'ar' ? 'اتصل بالدعم' : 'Contact Support' }}
                                </a>
                                <a href="/" class="btn-secondary">
                                    {{ ($lang ?? 'en') == 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <!-- Footer -->
            @php
                $footerSettings = \App\Models\FooterSetting::getSettings();
            @endphp
            <footer class="main-footer">
                <div class="footer-content">
                    <div class="footer-top">
                        <div style="display: flex; align-items: center; gap: 0; margin: 0; padding: 0;">
                            @if($footerSettings->logo_path)
                                <div class="footer-logo-section" style="margin: 0; padding: 0;">
                                    <a href="{{ $footerSettings->ayrade_url ?? $footerSettings->website_url ?? url('/') }}" target="_blank" style="margin: 0; padding: 0; display: block;">
                                        <img src="{{ asset('storage/' . $footerSettings->logo_path) }}" alt="Ayrade Logo" class="footer-logo" style="margin: 0; display: block;">
                                    </a>
                                </div>
                            @endif
                            <div class="footer-text-section" style="margin: 0; padding: 0; padding-left: 100px; text-align: left;">
                                @if($footerSettings->copyright_text)
                                    <p class="footer-text">
                                        @php
                                            $copyrightText = $footerSettings->copyright_text;
                                            if ($footerSettings->ayrade_url) {
                                                $copyrightText = preg_replace('/\bAyrade\b/', '<a href="' . e($footerSettings->ayrade_url) . '" target="_blank" style="color: #D6BFBF; text-decoration: underline; transition: all 0.3s ease;" onmouseover="this.style.color=\'#4FADC0\'" onmouseout="this.style.color=\'#D6BFBF\'">Ayrade</a>', $copyrightText);
                                            }
                                            echo $copyrightText;
                                        @endphp
                                    </p>
                                @endif
                                @if($footerSettings->developer_text)
                                    <p class="footer-text">
                                        @php
                                            $developerText = $footerSettings->developer_text;
                                            if ($footerSettings->mahdid_anes_url) {
                                                $developerText = preg_replace('/\bMahdid Anes\b/', '<a href="' . e($footerSettings->mahdid_anes_url) . '" target="_blank" style="color: #D6BFBF; text-decoration: underline; transition: all 0.3s ease;" onmouseover="this.style.color=\'#4FADC0\'" onmouseout="this.style.color=\'#D6BFBF\'">Mahdid Anes</a>', $developerText);
                                            }
                                            echo $developerText;
                                        @endphp
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="footer-actions">
                            <div class="footer-links">
                                @if($footerSettings->facebook_url)
                                    <a href="{{ $footerSettings->facebook_url }}" class="footer-link" target="_blank">Facebook</a>
                                @endif
                                @if($footerSettings->twitter_url)
                                    <a href="{{ $footerSettings->twitter_url }}" class="footer-link" target="_blank">Twitter</a>
                                @endif
                                @if($footerSettings->instagram_url)
                                    <a href="{{ $footerSettings->instagram_url }}" class="footer-link" target="_blank">Instagram</a>
                                @endif
                                @if($footerSettings->linkedin_url)
                                    <a href="{{ $footerSettings->linkedin_url }}" class="footer-link" target="_blank">LinkedIn</a>
                                @endif
                                @if($footerSettings->youtube_url)
                                    <a href="{{ $footerSettings->youtube_url }}" class="footer-link" target="_blank">YouTube</a>
                                @endif
                                @if($footerSettings->support_url)
                                    <a href="{{ $footerSettings->support_url }}" class="footer-link" target="_blank">Support</a>
                                @endif
                                @if($footerSettings->help_url)
                                    <a href="{{ $footerSettings->help_url }}" class="footer-link" target="_blank">Help</a>
                                @endif
                                @if($footerSettings->maps_url)
                                    <a href="{{ $footerSettings->maps_url }}" class="footer-link" target="_blank">Our Location</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>

        <script>
            // Create floating particles (circles only)
            function createParticles() {
                const bg = document.querySelector('.animated-bg');
                
                // Create floating circles (particles) - more and faster
                for (let i = 0; i < 35; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    const size = Math.random() * 80 + 40;
                    particle.style.width = size + 'px';
                    particle.style.height = size + 'px';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.top = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 8 + 's';
                    particle.style.animationDuration = (Math.random() * 3 + 6) + 's';
                    bg.appendChild(particle);
                }
            }

            createParticles();
        </script>
    </body>
</html>

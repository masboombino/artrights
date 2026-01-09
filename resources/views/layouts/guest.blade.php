<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ArtRights - Authentication</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('icons/thefavicon.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />

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
                font-family: 'Figtree', sans-serif;
                overflow-x: hidden;
                background-color: var(--bg-dark);
                color: var(--text-light);
                line-height: 1.6;
            }

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

            /* Header */
            .site-logo-text {
                font-family: 'Pacifico', cursive;
                font-size: 2rem;
                color: #36454f;
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .site-logo-text:hover {
                color: #D6BFBF;
                transform: scale(1.1) translateY(-3px);
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

            .auth-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 2rem 1rem;
                position: relative;
                z-index: 10;
            }

            .site-logo-container {
                text-align: center;
                margin-bottom: 2rem;
            }

            .site-logo-link {
                font-family: 'Pacifico', cursive;
                font-size: 4rem;
                font-weight: 700;
                color: #F3EBDD;
                text-decoration: none;
                text-shadow: 0 4px 8px rgba(0,0,0,0.3), 0 0 20px rgba(243, 235, 221, 0.5);
                letter-spacing: 2px;
                transition: all 0.3s ease;
                display: inline-block;
            }

            .site-logo-link:hover {
                transform: scale(1.05);
            }

            .site-tagline {
                margin-top: 1rem;
                font-size: 0.9rem;
                font-weight: 500;
                color: #F3EBDD;
                text-shadow: 0 2px 4px rgba(0,0,0,0.2);
                opacity: 0.95;
            }

            .form-container {
                width: 100%;
                max-width: 600px;
                background-color: #F3EBDD;
                border: 4px solid #193948;
                border-radius: 1rem;
                padding: 2.5rem;
                margin: 5px;
                position: relative;
                z-index: 10;
            }

            @media (max-width: 768px) {
                .site-logo-link {
                    font-size: 3rem;
                }

                .form-container {
                    padding: 1.5rem;
                }

                header {
                    padding: 0.75rem 1rem !important;
                }

                .nav-link-btn,
                .nav-btn-primary {
                    padding: 0.4rem 0.75rem;
                    font-size: 0.875rem;
                }
            }

            @media (max-width: 640px) {
                .site-logo-link {
                    font-size: 2rem;
                }

                .site-tagline {
                    font-size: 0.8rem;
                }

                .form-container {
                    padding: 1rem;
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

            @media (max-width: 768px) {
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
        </style>
    </head>
    <body>
        <div class="animated-bg"></div>

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

        <div class="auth-container">
            <!-- Form Container -->
            <div class="form-container">
                {{ $slot }}
            </div>
        </div>

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

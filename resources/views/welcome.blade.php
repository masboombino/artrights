<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ArtRights - Protecting Artists' Rights</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('icons/thefavicon.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&display=swap" rel="stylesheet" />

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
                margin: 5px;
                box-shadow: 0 2px 4px rgba(214, 191, 191, 0.3);
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
                margin: 5px;
                box-shadow: 0 2px 4px rgba(79, 173, 192, 0.3);
            }

            .nav-btn-primary:hover {
                background: #D6BFBF;
                color: #193948;
                transform: translateY(-2px) scale(1.05);
                box-shadow: 0 4px 8px rgba(214, 191, 191, 0.4);
            }

            /* Hero Section */
            .hero-section {
                min-height: 100vh;
                display: flex;
                align-items: flex-start;
                justify-content: center;
                position: relative;
                padding: 1rem 2rem 4rem;
                overflow: hidden;
                padding-top: 3rem;
            }

            .hero-content {
                max-width: 1200px;
                text-align: center;
                z-index: 10;
                position: relative;
                margin-top: 2rem;
            }

            .hero-title {
                font-family: 'Pacifico', cursive;
                font-size: clamp(2.5rem, 6vw, 4rem);
                font-weight: 400;
                color: var(--text-light);
                margin-bottom: 1.5rem;
                line-height: 1.2;
                text-shadow: 0 4px 8px rgba(0,0,0,0.3), 0 0 20px rgba(243, 235, 221, 0.5);
                animation: fadeInUp 1s ease 0.2s both;
                white-space: nowrap;
            }

            .hero-subtitle {
                font-size: clamp(1.2rem, 3vw, 1.8rem);
                color: rgba(243, 235, 221, 0.9);
                margin-bottom: 2rem;
                font-weight: 400;
                line-height: 1.6;
                animation: fadeInUp 1s ease 0.4s both;
                max-width: 800px;
                margin-left: auto;
                margin-right: auto;
            }

            .hero-container {
                background-color: transparent;
                border: 3px solid #D6BFBF;
                border-radius: 16px;
                padding: 3rem;
                margin: 0 auto;
                max-width: 900px;
                animation: fadeInUp 1s ease 0.6s both;
                box-shadow: 0 0 20px rgba(214, 191, 191, 0.4), 0 0 40px rgba(214, 191, 191, 0.2), inset 0 0 20px rgba(214, 191, 191, 0.1);
                animation: fadeInUp 1s ease 0.6s both, glow-border 3s ease-in-out infinite;
            }

            @keyframes glow-border {
                0%, 100% {
                    box-shadow: 0 0 20px rgba(214, 191, 191, 0.4), 0 0 40px rgba(214, 191, 191, 0.2), inset 0 0 20px rgba(214, 191, 191, 0.1);
                }
                50% {
                    box-shadow: 0 0 30px rgba(214, 191, 191, 0.6), 0 0 60px rgba(214, 191, 191, 0.4), inset 0 0 30px rgba(214, 191, 191, 0.2);
                }
            }

            .hero-description {
                font-size: clamp(1rem, 2vw, 1.2rem);
                color: rgba(243, 235, 221, 0.95);
                margin-bottom: 2rem;
                line-height: 1.8;
            }

            .hero-buttons {
                display: flex;
                gap: 1.5rem;
                justify-content: center;
                flex-wrap: wrap;
                animation: fadeInUp 1s ease 0.8s both;
            }

            .btn-hero {
                padding: 0.75rem 2rem;
                border-radius: 1rem;
                text-decoration: none;
                font-weight: 700;
                transition: all 0.3s ease;
                border: none;
                display: inline-block;
                box-shadow: 0 4px 6px rgba(79, 173, 192, 0.3);
            }

            .btn-hero-primary {
                background: #4FADC0;
                color: #193948;
            }

            .btn-hero-primary:hover {
                background: #D6BFBF;
                color: #193948;
                transform: translateY(-2px) scale(1.05);
                box-shadow: 0 6px 12px rgba(214, 191, 191, 0.4);
            }

            .btn-hero-secondary {
                background: #D6BFBF;
                color: #193948;
                box-shadow: 0 4px 6px rgba(214, 191, 191, 0.3);
            }

            .btn-hero-secondary:hover {
                background: #4FADC0;
                color: #193948;
                transform: translateY(-2px) scale(1.05);
                box-shadow: 0 6px 12px rgba(79, 173, 192, 0.4);
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(40px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Features Section */
            .features-section {
                padding: 3rem 2rem;
                position: relative;
            }

            .section-header {
                text-align: center;
                max-width: 800px;
                margin: 0 auto 5rem;
            }

            .section-badge {
                display: inline-block;
                padding: 0.5rem 1.5rem;
                background: rgba(79, 173, 192, 0.2);
                border: 2px solid rgba(79, 173, 192, 0.3);
                border-radius: 3rem;
                color: var(--accent-teal);
                font-size: 0.9rem;
                font-weight: 600;
                margin-bottom: 1.5rem;
            }

            .section-title {
                font-family: 'Playfair Display', serif;
                font-size: clamp(2.5rem, 6vw, 4.5rem);
                font-weight: 900;
                color: var(--text-light);
                margin-bottom: 1.5rem;
                line-height: 1.2;
            }

            .section-subtitle {
                font-size: clamp(1.1rem, 2.5vw, 1.4rem);
                color: rgba(243, 235, 221, 0.8);
                line-height: 1.6;
            }

            .features-grid {
                max-width: 1400px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 2.5rem;
            }

            .feature-card {
                background: rgba(243, 235, 221, 0.05);
                backdrop-filter: blur(10px);
                border: 2px solid rgba(243, 235, 221, 0.1);
                border-radius: 2rem;
                padding: 3rem;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .feature-card::after {
                content: '';
                position: absolute;
                top: -50%;
                right: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(79, 173, 192, 0.1) 0%, transparent 70%);
                opacity: 0;
                transition: opacity 0.4s ease;
            }

            .feature-card:hover::after {
                opacity: 1;
            }

            .feature-card:hover {
                transform: translateY(-15px);
                border-color: var(--accent-teal);
                box-shadow: 0 20px 50px rgba(79, 173, 192, 0.2);
                background: rgba(243, 235, 221, 0.08);
            }

            .feature-icon {
                font-size: 4rem;
                margin-bottom: 1.5rem;
                display: inline-block;
                animation: float-icon 3s ease-in-out infinite;
            }

            @keyframes float-icon {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }

            .feature-title {
                font-size: 1.75rem;
                font-weight: 700;
                color: var(--text-light);
                margin-bottom: 1rem;
            }

            .feature-description {
                font-size: 1.05rem;
                color: rgba(243, 235, 221, 0.8);
                line-height: 1.7;
            }

            /* About Section */
            .about-section {
                padding: 8rem 2rem;
                background: linear-gradient(180deg, transparent 0%, rgba(243, 235, 221, 0.03) 50%, transparent 100%);
            }

            .about-container {
                max-width: 1200px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 5rem;
                align-items: center;
            }

            .about-content h2 {
                font-family: 'Playfair Display', serif;
                font-size: clamp(2.5rem, 5vw, 4rem);
                font-weight: 900;
                color: var(--text-light);
                margin-bottom: 2rem;
                line-height: 1.2;
            }

            .about-content p {
                font-size: 1.2rem;
                color: rgba(243, 235, 221, 0.9);
                line-height: 1.8;
                margin-bottom: 1.5rem;
            }

            .about-visual {
                position: relative;
                height: 500px;
            }

            .visual-card {
                position: absolute;
                background: rgba(243, 235, 221, 0.1);
                backdrop-filter: blur(10px);
                border: 2px solid rgba(243, 235, 221, 0.2);
                border-radius: 2rem;
                padding: 2rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }

            .visual-card:nth-child(1) {
                top: 0;
                left: 0;
                width: 60%;
                animation: float-card 4s ease-in-out infinite;
            }

            .visual-card:nth-child(2) {
                bottom: 0;
                right: 0;
                width: 60%;
                animation: float-card 4s ease-in-out infinite 2s;
            }

            @keyframes float-card {
                0%, 100% { transform: translateY(0) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(2deg); }
            }

            /* CTA Section */
            .cta-section {
                padding: 8rem 2rem;
                position: relative;
                overflow: hidden;
            }

            .cta-container {
                max-width: 1000px;
                margin: 0 auto;
                text-align: center;
                background: linear-gradient(135deg, rgba(79, 173, 192, 0.2) 0%, rgba(25, 57, 72, 0.3) 100%);
                backdrop-filter: blur(20px);
                border: 2px solid rgba(243, 235, 221, 0.2);
                border-radius: 3rem;
                padding: 5rem 3rem;
                position: relative;
                overflow: hidden;
            }

            .cta-container::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(79, 173, 192, 0.1) 0%, transparent 70%);
                animation: rotate 15s linear infinite;
            }

            .cta-title {
                font-family: 'Playfair Display', serif;
                font-size: clamp(2.5rem, 6vw, 4rem);
                font-weight: 900;
                color: var(--text-light);
                margin-bottom: 1.5rem;
                position: relative;
                z-index: 1;
            }

            .cta-text {
                font-size: clamp(1.1rem, 2.5vw, 1.4rem);
                color: rgba(243, 235, 221, 0.9);
                margin-bottom: 3rem;
                position: relative;
                z-index: 1;
            }

            .cta-buttons {
                display: flex;
                gap: 1.5rem;
                justify-content: center;
                flex-wrap: wrap;
                position: relative;
                z-index: 1;
            }

            /* Footer */
            .main-footer {
                background: linear-gradient(180deg, transparent 0%, rgba(25, 57, 72, 0.5) 100%);
                padding: 4rem 2rem 2rem;
                border-top: 3px solid rgba(243, 235, 221, 0.2);
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

            /* Responsive */
            @media (max-width: 1024px) {
                .about-container {
                    grid-template-columns: 1fr;
                    gap: 3rem;
                }

                .about-visual {
                    height: 400px;
                }
            }

            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.5rem;
                    white-space: normal;
                }

                .section-title {
                    font-size: 2.5rem;
                }

                .features-grid {
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

                .site-logo-text {
                    font-size: 1.5rem;
                }
            }

            @media (max-width: 640px) {
                .hero-section {
                    padding: 1rem;
                    padding-top: 2rem;
                }

                .hero-container {
                    padding: 2rem 1.5rem;
                }

                .btn-hero {
                    padding: 0.75rem 1.5rem;
                    font-size: 0.95rem;
                }
            }
        </style>
    </head>

    <body>
        <div class="animated-bg"></div>

        <!-- Header -->
        <header style="background-color: #F3EBDD; border-bottom: 4px solid #193948; padding: 1rem 2rem; position: relative; min-height: 85px; display: flex; align-items: center;">
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
                    <div class="flex items-center gap-2 flex-wrap">
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

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Protect Your Creative Works</h1>
                <p class="hero-subtitle">A platform designed for protecting intellectual property rights of artists in Algeria</p>
                <div class="hero-container">
                    <p class="hero-description">
                        Register your artistic works, connect with provincial agencies across the country, and ensure your creations are legally protected through our management system. You will receive compensation for your artworks that are used illegally.
                    </p>
                    <div class="hero-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-hero btn-hero-primary">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">Get Started</a>
                            <a href="{{ route('login') }}" class="btn-hero btn-hero-secondary">Log In</a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section" id="features">
            <div class="section-header">
                <div class="section-badge">Platform Services</div>
                <h2 class="section-title">Everything You Need to Protect Your Art</h2>
                <p class="section-subtitle">A complete ecosystem designed to safeguard your intellectual property rights with official registration, field inspections, and legal protection</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h3 class="feature-title">Official Artist Registration</h3>
                    <p class="feature-description">
                        Complete registration system with identity verification and document validation. Get officially recognized as an artist with our streamlined process.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎨</div>
                    <h3 class="feature-title">Artwork Registration</h3>
                    <p class="feature-description">
                        Register all your creative works including music, books, films, paintings, digital art, and more. Each work gets a unique registration ID.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏛️</div>
                    <h3 class="feature-title">Nationwide Agency Network</h3>
                    <p class="feature-description">
                        Comprehensive coverage through 58 provincial agencies across Algeria. Local support and management in every region.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👮</div>
                    <h3 class="feature-title">Field Inspection System</h3>
                    <p class="feature-description">
                        Professional agents conduct on-site inspections to detect and document unauthorized use of your registered artworks.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3 class="feature-title">Automated Compensation</h3>
                    <p class="feature-description">
                        Smart calculation and processing system for compensation when violations are verified. Fast and transparent payment processing.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📋</div>
                    <h3 class="feature-title">Complaint Management</h3>
                    <p class="feature-description">
                        File formal complaints for unauthorized use. Track your complaints through the entire process with real-time updates.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">Legal Protection</h3>
                    <p class="feature-description">
                        Full legal framework support with process-verbals (PVs) and official documentation. Your rights are legally enforceable.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Analytics & Reports</h3>
                    <p class="feature-description">
                        Track your artworks' status, view usage statistics, monitor violations, and access comprehensive reports.
                    </p>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section" id="about">
            <div class="about-container">
                <div class="about-content">
                    <h2>About ArtRights</h2>
                    <p>
                        ArtRights is a platform dedicated to protecting intellectual property rights of artists throughout Algeria. 
                        We provide a system that bridges the gap between artists and legal protection.
                    </p>
                    <p>
                        Our mission is to ensure that every creative work is properly registered, legally protected, and that artists receive 
                        fair compensation for the use of their intellectual property. Through our network of 58 provincial agencies, we bring 
                        protection services directly to artists in every region.
                    </p>
                    <p>
                        With field inspection systems, compensation processing, and a complete complaint management framework, 
                        ArtRights offers a solution for intellectual property protection in Algeria.
                    </p>
                </div>
                <div class="about-visual">
                    <div class="visual-card">
                        <h3 style="color: var(--text-light); margin-bottom: 1rem; font-size: 1.5rem;">🎯 Our Mission</h3>
                        <p style="color: rgba(243, 235, 221, 0.9); line-height: 1.6;">
                            To protect and preserve the intellectual property rights of every artist in Algeria through technology and legal support.
                        </p>
                    </div>
                    <div class="visual-card">
                        <h3 style="color: var(--text-light); margin-bottom: 1rem; font-size: 1.5rem;">✨ Our Vision</h3>
                        <p style="color: rgba(243, 235, 221, 0.9); line-height: 1.6;">
                            To protect artists' rights, reduce piracy and unauthorized use of creative works, and ensure that every artist receives fair compensation for their intellectual property.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="cta-container">
                <h2 class="cta-title">Ready to Protect Your Art?</h2>
                <p class="cta-text">Join artists who trust ArtRights to safeguard their creative works and intellectual property rights</p>
                <div class="cta-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-hero btn-hero-primary">Go to Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">Start Protecting Your Art</a>
                        <a href="{{ route('login') }}" class="btn-hero btn-hero-secondary">Log In</a>
                    @endauth
                </div>
            </div>
        </section>

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

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Law & Regulations - ArtRights</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            :root {
                --primary-dark: #193948;
                --primary-light: #2a4a5a;
                --accent-teal: #4FADC0;
                --accent-cream: #D6BFBF;
                --bg-cream: #F3EBDD;
                --bg-dark: #36454f;
                --text-light: #F3EBDD;
            }
            body {
                font-family: 'Figtree', sans-serif;
                background-color: var(--bg-dark);
                color: var(--text-light);
                line-height: 1.6;
            }
            .animated-bg {
                position: fixed;
                top: 0; left: 0; width: 100%; height: 100%;
                z-index: -1;
                background: linear-gradient(135deg, #193948 0%, #2a4a5a 50%, #36454f 100%);
            }
            /* Header Styles */
            .site-logo-text {
                font-family: 'Pacifico', cursive;
                font-size: 1.5rem;
                color: #36454f;
                text-decoration: none;
            }
            .nav-link-btn {
                background: #D6BFBF;
                color: #193948;
                text-decoration: none;
                font-weight: 600;
                padding: 0.5rem 1rem;
                border-radius: 1rem;
                transition: all 0.3s ease;
            }
            .nav-link-btn:hover {
                background: #4FADC0;
                transform: translateY(-2px);
            }

            /* Main Content */
            .container {
                max-width: 1000px;
                margin: 0 auto;
                padding: 2rem;
            }
            
            .page-title {
                font-family: 'Playfair Display', serif;
                font-size: clamp(2rem, 5vw, 3rem);
                text-align: center;
                margin-bottom: 2rem;
                color: var(--text-light);
            }

            /* Tabs */
            .tabs {
                display: flex;
                justify-content: center;
                gap: 1rem;
                margin-bottom: 2rem;
                flex-wrap: wrap;
            }
            .tab-btn {
                background: rgba(243, 235, 221, 0.1);
                border: 2px solid rgba(243, 235, 221, 0.2);
                color: var(--text-light);
                padding: 0.75rem 1.5rem;
                border-radius: 2rem;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            .tab-btn.active {
                background: var(--accent-teal);
                color: var(--primary-dark);
                border-color: var(--accent-teal);
            }
            .tab-btn:hover:not(.active) {
                background: rgba(243, 235, 221, 0.2);
            }

            /* Law Content */
            .law-content {
                display: none;
                animation: fadeIn 0.5s ease;
            }
            .law-content.active {
                display: block;
            }
            
            .law-card {
                background: rgba(243, 235, 221, 0.05);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(243, 235, 221, 0.1);
                border-radius: 1.5rem;
                padding: 2.5rem;
                margin-bottom: 2rem;
            }

            .law-title {
                font-family: 'Playfair Display', serif;
                font-size: 2rem;
                margin-bottom: 1rem;
                color: var(--accent-teal);
            }

            .notice-box {
                background: rgba(231, 98, 104, 0.2);
                border-left: 4px solid #E76268;
                padding: 1rem;
                border-radius: 0.5rem;
                margin-bottom: 2rem;
                font-style: italic;
            }

            .section {
                margin-bottom: 2.5rem;
            }
            .section-title {
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                color: #D6BFBF;
                border-bottom: 1px solid rgba(214, 191, 191, 0.3);
                padding-bottom: 0.5rem;
            }
            .highlight-box {
                background: rgba(79, 173, 192, 0.15);
                border: 1px solid var(--accent-teal);
                padding: 1.5rem;
                border-radius: 1rem;
                margin-top: 1rem;
            }
            .formula-box {
                background: rgba(0, 0, 0, 0.2);
                font-family: monospace;
                padding: 1rem;
                border-radius: 0.5rem;
                margin: 1rem 0;
                border: 1px dashed rgba(243, 235, 221, 0.3);
            }
            .law-list {
                list-style-type: none;
                margin-top: 1rem;
            }
            .law-list li {
                position: relative;
                padding-left: 1.5rem;
                margin-bottom: 0.5rem;
            }
            .law-list li::before {
                content: '•';
                position: absolute;
                left: 0;
                color: var(--accent-teal);
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Arabic support */
            .rtl {
                direction: rtl;
                text-align: right;
            }
            .rtl .notice-box {
                border-left: none;
                border-right: 4px solid #E76268;
            }
            .rtl .law-list li {
                padding-left: 0;
                padding-right: 1.5rem;
            }
            .rtl .law-list li::before {
                left: auto;
                right: 0;
            }
        </style>
    </head>
    <body>
        <div class="animated-bg"></div>

        <!-- Compact Header -->
        <header style="background-color: #F3EBDD; border-bottom: 4px solid #193948; padding: 0.75rem 2rem; display: flex; align-items: center; justify-content: space-between;">
            <div class="flex items-center gap-3">
                <a href="/" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="{{ asset('icons/logo.png') }}" alt="ArtRights Logo" style="width: 30px; height: auto;">
                    <span class="site-logo-text ml-2">ArtRights</span>
                </a>
            </div>
            <div>
                <a href="/" class="nav-link-btn">Back to Home</a>
            </div>
        </header>

        <div class="container">
            <h1 class="page-title">Laws & Regulations</h1>

            <div class="tabs">
                <button class="tab-btn active" onclick="switchTab('english')">English</button>
                <button class="tab-btn" onclick="switchTab('french')">Français</button>
                <button class="tab-btn" onclick="switchTab('arabic')">العربية</button>
            </div>

            <!-- English Content -->
            <div id="english" class="law-content active">
                @if($englishLaw)
                    <div class="law-card">
                        <h2 class="law-title">{{ $englishLaw->title }}</h2>
                        @if($englishLaw->notice)
                            <div class="notice-box">{{ $englishLaw->notice }}</div>
                        @endif

                        @if(is_array($englishLaw->sections))
                            @foreach($englishLaw->sections as $section)
                                <div class="section {{ isset($section['highlight']) && $section['highlight'] ? 'highlight-box' : '' }}">
                                    <h3 class="section-title">{{ $section['title'] ?? '' }}</h3>
                                    <p>{{ $section['content'] ?? '' }}</p>

                                    @if(isset($section['formula']) && $section['formula'])
                                        <div class="formula-box">{{ $section['formula'] }}</div>
                                    @endif

                                    @if(isset($section['items']) && is_array($section['items']))
                                        <ul class="law-list">
                                            @foreach($section['items'] as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <div class="law-card" style="text-align: center;">
                        <p>No English content available.</p>
                    </div>
                @endif
            </div>

            <!-- French Content -->
            <div id="french" class="law-content">
                @if($frenchLaw)
                    <div class="law-card">
                        <h2 class="law-title">{{ $frenchLaw->title }}</h2>
                        @if($frenchLaw->notice)
                            <div class="notice-box">{{ $frenchLaw->notice }}</div>
                        @endif

                        @if(is_array($frenchLaw->sections))
                            @foreach($frenchLaw->sections as $section)
                                <div class="section {{ isset($section['highlight']) && $section['highlight'] ? 'highlight-box' : '' }}">
                                    <h3 class="section-title">{{ $section['title'] ?? '' }}</h3>
                                    <p>{{ $section['content'] ?? '' }}</p>
                                    
                                    @if(isset($section['formula']) && $section['formula'])
                                        <div class="formula-box">{{ $section['formula'] }}</div>
                                    @endif

                                    @if(isset($section['items']) && is_array($section['items']))
                                        <ul class="law-list">
                                            @foreach($section['items'] as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <div class="law-card" style="text-align: center;">
                        <p>Aucun contenu disponible.</p>
                    </div>
                @endif
            </div>

            <!-- Arabic Content -->
            <div id="arabic" class="law-content rtl">
                @if($arabicLaw)
                    <div class="law-card">
                        <h2 class="law-title">{{ $arabicLaw->title }}</h2>
                        @if($arabicLaw->notice)
                            <div class="notice-box">{{ $arabicLaw->notice }}</div>
                        @endif

                        @if(is_array($arabicLaw->sections))
                            @foreach($arabicLaw->sections as $section)
                                <div class="section {{ isset($section['highlight']) && $section['highlight'] ? 'highlight-box' : '' }}">
                                    <h3 class="section-title">{{ $section['title'] ?? '' }}</h3>
                                    <p>{{ $section['content'] ?? '' }}</p>
                                    
                                    @if(isset($section['formula']) && $section['formula'])
                                        <div class="formula-box" style="direction: ltr; text-align: center;">{{ $section['formula'] }}</div>
                                    @endif

                                    @if(isset($section['items']) && is_array($section['items']))
                                        <ul class="law-list">
                                            @foreach($section['items'] as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <div class="law-card" style="text-align: center;">
                        <p>لا يوجد محتوى متاح.</p>
                    </div>
                @endif
            </div>

        </div>

        <script>
            function switchTab(lang) {
                // Hide all content
                document.querySelectorAll('.law-content').forEach(el => el.classList.remove('active'));
                document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

                // Show selected
                document.getElementById(lang).classList.add('active');
                
                // Activate button
                // Find button by text or index.. simpler to add ID or check onclick, 
                // but let's just use event.currentTarget if we passed event, 
                // or simplistic approach: loop buttons and match text? 
                // simpler: just re-render is fast enough? no, JS approach:
                
                const buttons = document.querySelectorAll('.tab-btn');
                if(lang === 'english') buttons[0].classList.add('active');
                if(lang === 'french') buttons[1].classList.add('active');
                if(lang === 'arabic') buttons[2].classList.add('active');
            }
        </script>
    </body>
</html>

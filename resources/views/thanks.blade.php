<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Thank You - ArtRights</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/thefavicon.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #36454f 0%, #2a3a42 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Floating Hearts Background */
        .hearts-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
            pointer-events: none;
        }

        .heart {
            position: absolute;
            font-size: 24px;
            color: rgba(255, 255, 255, 0.3);
            animation: floatUp 20s infinite linear;
            opacity: 0;
        }

        @keyframes floatUp {
            0% {
                transform: translateY(100vh) translateX(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) translateX(100px) rotate(360deg);
                opacity: 0;
            }
        }

        .heart:nth-child(odd) { left: 10%; }
        .heart:nth-child(even) { left: 90%; }

        /* Main Content */
        .main-content {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            padding: 3rem 1.5rem;
        }

        .thanks-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 4rem;
            padding: 2rem 0;
        }

        .page-title {
            font-family: 'Pacifico', cursive;
            font-size: 4rem;
            font-weight: 400;
            color: #F3EBDD;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: 2px;
            animation: title-glow 2s ease-in-out infinite;
            position: relative;
        }

        @keyframes title-glow {
            0%, 100% {
                text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3), 0 0 20px rgba(214, 191, 191, 0.6);
            }
            50% {
                text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3), 0 0 40px rgba(214, 191, 191, 0.9), 0 0 60px rgba(214, 191, 191, 0.8), 0 0 80px rgba(214, 191, 191, 0.6);
            }
        }

        .section-card {
            background: #F3EBDD;
            border-radius: 20px;
            padding: 3rem 2.5rem;
            margin-bottom: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            border: 3px solid #193948;
            position: relative;
            overflow: hidden;
        }

        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #D6BFBF, #4FADC0, #D6BFBF);
        }

        .section-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-divider {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #193948, transparent);
            margin: 1rem auto;
        }

        .text-columns {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .text-column {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 15px;
            border-left: 4px solid #193948;
        }

        .text-column.arabic {
            direction: rtl;
            border-left: none;
            border-right: 4px solid #193948;
        }

        .text-content {
            font-size: 1.05rem;
            line-height: 1.9;
            color: #193948;
            text-align: center;
        }

        .names-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .name-card {
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            color: #F3EBDD;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            font-weight: 600;
            font-size: 1.2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3), 0 0 30px rgba(214, 191, 191, 0.5);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: visible;
            animation: name-card-glow 3s ease-in-out infinite;
        }

        @keyframes name-card-glow {
            0%, 100% {
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3), 0 0 30px rgba(214, 191, 191, 0.6), 0 0 50px rgba(214, 191, 191, 0.4);
            }
            50% {
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3), 0 0 50px rgba(214, 191, 191, 0.9), 0 0 80px rgba(214, 191, 191, 0.8), 0 0 120px rgba(214, 191, 191, 0.6);
            }
        }

        .name-card::before {
            content: '♥';
            position: absolute;
            top: -10px;
            left: 10px;
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            animation: heart-float 4s ease-in-out infinite;
        }

        .name-card::after {
            content: '♥';
            position: absolute;
            bottom: -10px;
            right: 10px;
            font-size: 1.5rem;
            color: rgba(0, 0, 0, 0.6);
            animation: heart-float 4s ease-in-out infinite 2s;
        }

        @keyframes heart-float {
            0%, 100% {
                transform: translateY(0) scale(1);
                opacity: 0.8;
            }
            25% {
                transform: translateY(-15px) scale(1.2);
                opacity: 1;
            }
            50% {
                transform: translateY(-25px) scale(1.1);
                opacity: 0.9;
            }
            75% {
                transform: translateY(-15px) scale(1.2);
                opacity: 1;
            }
        }

        .name-card:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 15px 40px rgba(214, 191, 191, 0.5), 0 0 60px rgba(214, 191, 191, 0.9), 0 0 100px rgba(214, 191, 191, 0.7);
            background: linear-gradient(135deg, #D6BFBF 0%, #c4a8a8 100%);
            color: #193948;
        }

        .name-card:nth-child(odd)::before {
            color: rgba(255, 255, 255, 0.9);
        }

        .name-card:nth-child(even)::before {
            color: rgba(0, 0, 0, 0.7);
        }

        .name-card:nth-child(odd)::after {
            color: rgba(0, 0, 0, 0.7);
        }

        .name-card:nth-child(even)::after {
            color: rgba(255, 255, 255, 0.9);
        }

        .name-card.white-heart::before,
        .name-card.white-heart::after {
            content: '🤍';
            color: rgba(255, 255, 255, 0.9);
        }


        .footer-section {
            text-align: center;
            margin-top: 4rem;
            padding-top: 2rem;
        }

        .btn-home {
            display: inline-block;
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            color: #F3EBDD;
            padding: 1rem 3rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            border: 2px solid #F3EBDD;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            background: linear-gradient(135deg, #D6BFBF 0%, #c4a8a8 100%);
            color: #193948;
            border-color: #193948;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .section-card {
                padding: 2rem 1.5rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .text-columns {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .names-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 2rem 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.3rem;
            }

            .text-content {
                font-size: 0.95rem;
            }

            .name-card {
                font-size: 1rem;
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Hearts Background -->
    <div class="hearts-container">
        <div class="heart">♥</div>
        <div class="heart">♥</div>
        <div class="heart">♥</div>
        <div class="heart">♥</div>
        <div class="heart">♥</div>
        <div class="heart">♥</div>
        <div class="heart">♥</div>
        <div class="heart">♥</div>
        <div class="heart">♥</div>
        <div class="heart">♥</div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="thanks-wrapper">
            <div class="page-header">
                <h1 class="page-title">Thanks To</h1>
            </div>

            <!-- Father Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">My Parents - لوالديّ</h2>
                    <div class="section-divider"></div>
                </div>
                <div class="text-columns">
                    <div class="text-column french">
                        <div class="text-content">
                            Tout d'abord, je remercie mes parents, mon père et ma mère, et plus particulièrement mon cher père, qui m'a soutenu tout au long de ces trois années par tous les moyens possibles, afin que je puisse poursuivre et achever mes études, quelles que soient les circonstances et les difficultés. Merci à toi, mon père Essalih Mahdid, pour tout ce que tu as fait pour moi. ♥
                        </div>
                    </div>
                    <div class="text-column arabic">
                        <div class="text-content">
                            أولًا، أشكر والديّ، أبي وأمي، وخاصة والدي العزيز الذي دعمني طوال هذه السنوات الثلاث بكل ما استطاع، ووقف إلى جانبي لأكمل دراستي دون انقطاع، مهما كانت الظروف ومهما تطلّب الأمر. شكرًا لك يا أبي الصالح محديد، جزاك الله عني كل خير. ♥
                        </div>
                    </div>
                    <div class="text-column english">
                        <div class="text-content">
                            First of all, I would like to thank my parents, my father and my mother, and especially my dear father, who supported me throughout these three years in every possible way, so that I could continue and complete my studies, no matter the circumstances or the challenges. Thank you, my father Essalih Mahdid, for everything you have done for me. ♥
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teachers Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">My Teachers - لأستاذتي</h2>
                    <div class="section-divider"></div>
                </div>
                <div class="text-columns">
                    <div class="text-column french">
                        <div class="text-content">
                            Je suis profondément reconnaissant envers mes professeures préférées, Madame Ouedfel et Madame Abadi. Je leur adresse mes sincères remerciements et toute ma reconnaissance, car elles ont été parmi les meilleures enseignantes tout au long de mon parcours académique, et ont grandement contribué à développer ma passion pour ce domaine. ♥
                        </div>
                    </div>
                    <div class="text-column arabic">
                        <div class="text-content">
                            أنا ممتنّ جدًا جدًا لأستاذتي المفضّلتين لديّ، السيدة Ouedfel والسيدة Abadi. سأبقى أتذكرهما وأتحدث عنهما إلى الأبد. أتقدم لهما بخالص الشكر والتقدير، فهما من أفضل الأساتذة الذين رافقوني طوال مشواري الدراسي، وكان لهما الدور الأكبر في تنمية حبي وشغفي بهذا المجال. لهما مني كل الامتنان والاحترام. ♥
                        </div>
                    </div>
                    <div class="text-column english">
                        <div class="text-content">
                            I am deeply grateful to my favorite professors, Mrs. Ouedfel and Mrs. Abadi. I extend my sincere thanks and appreciation to them, as they were among the best teachers throughout my academic journey and played a major role in developing my love and passion for this field. ♥
                        </div>
                    </div>
                </div>
                <div class="names-grid">
                    <div class="name-card">السيدة Ouedfel</div>
                    <div class="name-card">السيدة Abadi</div>
                </div>
            </div>

            <!-- Friends Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">My Friends - لأصدقائي</h2>
                    <div class="section-divider"></div>
                </div>
                <div class="text-columns">
                    <div class="text-column french">
                        <div class="text-content">
                            Je suis profondément reconnaissant envers mes amis Alilou Lehnaya, Abdelraouf Mohoubi et Moussa Dani. Grâce à leur soutien, à leurs attitudes sincères et à leur présence à mes côtés dans différentes circonstances, j'ai pu achever mes études durant ces trois années. ♥
                        </div>
                    </div>
                    <div class="text-column arabic">
                        <div class="text-content">
                            أنا ممتنّ جدًا لأصدقائي عليلو لهناية، عبد الرؤوف موهوبي، وموسى داني. بفضلهم وبفضل مواقفهم الصادقة ووقوفهم معي في مختلف الظروف، استطعت إكمال دراستي خلال هذه السنوات الثلاث. لهم مني كل الشكر والامتنان. ♥
                        </div>
                    </div>
                    <div class="text-column english">
                        <div class="text-content">
                            I am deeply grateful to my friends Alilou Lehnaya, Abdelraouf Mohoubi, and Moussa Dani. Thanks to their support, their sincerity, and their standing by me through different circumstances, I was able to complete my studies over these three years. ♥
                        </div>
                    </div>
                </div>
                <div class="names-grid">
                    <div class="name-card">عليلو لهناية</div>
                    <div class="name-card">عبد الرؤوف موهوبي</div>
                    <div class="name-card">موسى داني</div>
                    <div class="name-card white-heart">عائلة موهوبي كلها 🤍</div>
                </div>
            </div>

            <!-- Company Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">To Ayrade Company</h2>
                    <div class="section-divider"></div>
                </div>
                <div class="text-columns">
                    <div class="text-column french">
                        <div class="text-content">
                            Je remercie la société Ayrade pour m'avoir accueilli durant une période de stage de six mois, et j'adresse un remerciement tout particulier à Madame Saloua Fedoul et Madame Imene Raheb. Elles ont été extrêmement aimables avec moi et m'ont fait aimer l'environnement de travail. Elles sont vraiment exceptionnelles. Je remercie également M. MOHAMED ADDA BENKOSSEIR pour m'avoir formé en Laravel avec sérieux et de tout son cœur, merci beaucoup mon frère. ♥
                        </div>
                    </div>
                    <div class="text-column arabic">
                        <div class="text-content">
                            أشكر شركة أيراد على احتضانها لي خلال فترة التربص التي دامت ستة أشهر، وأخصّ بالشكر السيدتين Saloua Fedoul وImene Raheb. لقد كانتا ودودتين جدًا جدًا معي، وساعدتاني على حبّ مكان العمل والشعور بالراحة فيه. إنهما حقًا من الأفضل. أشكر السيد MOHAMED ADDA BENKOSSEIR على تدريبه لي تخصص Laravel بجدية ومن قلبه، شكرًا جزيلًا أخي. ♥
                        </div>
                    </div>
                    <div class="text-column english">
                        <div class="text-content">
                            I would like to thank Ayrade Company for hosting me during my six-month internship, with special thanks to Ms. Saloua Fedoul and Ms. Imene Raheb. They were extremely kind and welcoming, and they made me truly enjoy the workplace. They are truly the best. I also thank Mr. MOHAMED ADDA BENKOSSEIR for training me in Laravel with seriousness and from his heart, thank you very much my brother. ♥
                        </div>
                    </div>
                </div>
                <div class="names-grid">
                    <div class="name-card">السيدة Saloua Fedoul</div>
                    <div class="name-card"> MOHAMED ADDA BENKOSSEIR</div>
                    <div class="name-card">السيدة Imene Raheb</div>
                </div>
            </div>

            <div class="footer-section">
                <a href="/" class="btn-home">الصفحة الرئيسية</a>
            </div>
        </div>
    </div>

    <script>
        // Add more floating hearts dynamically
        const heartsContainer = document.querySelector('.hearts-container');
        
        for (let i = 0; i < 15; i++) {
            const heart = document.createElement('div');
            heart.className = 'heart';
            heart.textContent = '♥';
            heart.style.left = Math.random() * 100 + '%';
            heart.style.animationDelay = Math.random() * 20 + 's';
            heart.style.animationDuration = (Math.random() * 10 + 15) + 's';
            heartsContainer.appendChild(heart);
        }
    </script>
</body>
</html>

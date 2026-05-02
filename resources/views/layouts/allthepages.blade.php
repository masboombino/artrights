<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1{{ isset($disableZoom) && $disableZoom ? ', maximum-scale=1, user-scalable=no' : '' }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ArtRights - {{ $pageTitle ?? 'Dashboard' }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('icons/thefavicon.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            * {
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Figtree', sans-serif;
                background-color: #36454f;
                margin: 0;
                padding: 0;
            }

            .site-logo-text {
                font-family: 'Pacifico', cursive;
                font-size: 2rem;
                color: #36454f;
                text-decoration: none;
                transition: all 0.3s ease;
                line-height: 1;
                display: flex;
                align-items: center;
                height: 100%;
                padding: 0;
                margin: -1rem 0;
            }

            .site-logo-text:hover {
                transform: scale(1.1) translateY(-3px);
            }

            .header-button {
                background-color: #D6BFBF;
                color: #F3EBDD;
                padding: 0.5rem 1.5rem;
                border-radius: 1rem;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-block;
                margin: 5px;
                border: none;
                cursor: pointer;
            }

            .header-button:hover {
                background-color: #F3EBDD;
                color: #D6BFBF;
                transform: translateY(-2px);
                
            }

            .danger-button {
                background-color: #E76268;
                color: white;
                padding: 0.5rem 1.5rem;
                border-radius: 1rem;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-block;
                margin: 5px;
                border: none;
                cursor: pointer;
            }

            .danger-button:hover {
                background-color: #c54d52;
                color: white;
                transform: scale(1.05);
            }

            .notification-icon {
                position: relative;
                width: 42px;
                height: 42px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background-color: #193948;
                color: #F3EBDD;
                transition: all 0.3s ease;
                margin: 0;
                border: none;
                cursor: pointer;
                
            }

            .notification-icon:hover {
                transform: translateY(-2px);
                
            }

            .notification-badge {
                position: absolute;
                top: -4px;
                right: -4px;
                background-color: #E76268;
                color: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.7rem;
                font-weight: bold;
                border: 2px solid #F3EBDD;
            }

            .notifications-menu-container {
                position: relative;
            }

            .notifications-dropdown {
                position: absolute;
                top: calc(100% + 0.6rem);
                right: 0;
                width: min(380px, 92vw);
                background: linear-gradient(180deg, #F3EBDD 0%, #e8ddd0 100%);
                border: 4px solid #ffffff;
                border-radius: 1rem;
                box-shadow: 0 10px 28px rgba(25, 57, 72, 0.35);
                z-index: 1200;
                opacity: 0;
                transform: translateY(-8px);
                pointer-events: none;
                transition: all 0.2s ease;
                overflow: hidden;
            }

            @media (max-width: 768px) {
                .notifications-menu-container {
                    position: static;
                }

                .notifications-dropdown {
                    position: fixed;
                    top: 78px;
                    left: 50% !important;
                    right: auto !important;
                    width: min(430px, calc(100vw - 0.8rem)) !important;
                    max-height: calc(100vh - 70px);
                    transform: translate(-50%, -8px) !important;
                    display: flex;
                    flex-direction: column;
                    z-index: 2000;
                }

                .notifications-dropdown.open {
                    transform: translate(-50%, 0) !important;
                }

                .notifications-dropdown-list {
                    max-height: none;
                    flex: 1;
                    overflow-y: auto;
                }
            }

            @media (max-width: 480px) {
                .notifications-dropdown {
                    top: 66px;
                    width: calc(100vw - 0.5rem) !important;
                    max-height: calc(100vh - 56px);
                }
            }

            .notifications-dropdown.open {
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
            }

            .notifications-dropdown-header {
                padding: 0.8rem 1rem;
                background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
                color: #F3EBDD;
                font-weight: 700;
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 0.92rem;
            }

            .notifications-dropdown-list {
                max-height: 390px;
                overflow-y: auto;
                padding: 0.4rem;
                display: flex;
                flex-direction: column;
                gap: 0.35rem;
            }

            .notifications-dropdown-item {
                display: block;
                text-decoration: none;
                color: #193948;
                background: rgba(255, 255, 255, 0.55);
                border: 1px solid rgba(25, 57, 72, 0.18);
                border-radius: 0.7rem;
                padding: 0.55rem 2rem 0.55rem 0.65rem;
                transition: all 0.2s ease;
                position: relative;
            }

            .notifications-dropdown-item.unread {
                background: #ffffff;
                border-color: rgba(79, 173, 192, 0.5);
                box-shadow: inset 3px 0 0 #4FADC0;
            }

            .notifications-dropdown-item:hover {
                transform: translateY(-1px);
                border-color: rgba(25, 57, 72, 0.4);
            }

            .notifications-dropdown-item-title {
                font-size: 0.85rem;
                font-weight: 700;
                margin-bottom: 0.2rem;
                display: flex;
                align-items: center;
                gap: 0.35rem;
            }

            .notifications-dropdown-item-message {
                font-size: 0.78rem;
                opacity: 0.9;
                line-height: 1.3;
                margin-bottom: 0.2rem;
            }

            .notifications-dropdown-item-time {
                font-size: 0.7rem;
                opacity: 0.65;
            }

            .notifications-dropdown-delete {
                position: absolute;
                top: 0;
                right: 0;
                width: 1.8rem;
                height: 1.8rem;
                border: none;
                border-radius: 0 0.7rem 0 0.55rem;
                background: rgba(231, 98, 104, 0.22);
                color: #b93139;
                font-weight: 800;
                font-size: 0.95rem;
                line-height: 1;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
            }

            .notifications-dropdown-delete:hover {
                background: rgba(231, 98, 104, 0.36);
                transform: none;
            }

            .notifications-dropdown-empty {
                text-align: center;
                color: #193948;
                padding: 1rem 0.75rem;
                font-size: 0.82rem;
                opacity: 0.75;
            }

            .notifications-dropdown-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0;
                padding: 0;
                border-top: 1px solid rgba(25, 57, 72, 0.16);
                background: rgba(25, 57, 72, 0.05);
            }

            .notifications-dropdown-btn {
                border: none;
                border-radius: 0;
                padding: 0.7rem 0.6rem;
                font-size: 0.76rem;
                font-weight: 700;
                cursor: pointer;
                text-align: center;
                text-decoration: none;
                transition: all 0.2s ease;
                width: 100%;
                height: 100%;
            }

            .notifications-dropdown-btn.primary {
                background: #193948;
                color: #F3EBDD;
            }

            .notifications-dropdown-btn.secondary {
                background: #D6BFBF;
                color: #193948;
            }

            .notifications-dropdown-actions form {
                margin: 0;
            }

            .notifications-dropdown-actions form:first-child .notifications-dropdown-btn {
                border-right: 1px solid rgba(25, 57, 72, 0.2);
            }

            .notifications-dropdown-btn:hover {
                transform: translateY(-1px);
                filter: brightness(1.05);
            }

            .notifications-dropdown-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
                transform: none;
                filter: none;
            }

            .profile-button {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                border: 2px solid #D6BFBF;
                overflow: hidden;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background-color: #F3EBDD;
                color: #193948;
                font-weight: 700;
                transition: all 0.3s ease;
                text-decoration: none;
                margin: 5px;
            }

            .profile-button:hover {
                transform: translateY(-2px) scale(1.05);
                
            }

            .profile-button img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .page-title-section {
                width: 100%;
                display: flex;
                justify-content: center;
                padding: 0px 20px 0px 0px;
                background-color: transparent;
                transform: translate(10px, -20px);
                margin-bottom: -20px;
            }

            .page-title-badge {
                position: relative;
                background-color: #F3EBDD;
                color: #36454f;
                padding: 8px 20px;
                border-radius: 1rem;
                font-size: 1.25rem;
                font-weight: 700;
                white-space: nowrap;
                z-index: 10;
                border: 3px solid #36454f;
                line-height: 1;
                pointer-events: none;
            }

            /* في الهاتف والتابلت فقط - اسم الصفحة ملتصق بالهيدر */
            @media (max-width: 1024px) {
                .page-title-badge {
                    display: block !important;
                    position: sticky;
                    top: 70px; /* تحت الهيدر مباشرة */
                    margin-top: 0 !important;
                    z-index: 100;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    border-radius: 0 0 1rem 1rem;
                }
            }

            .main-content {
                padding: 5px;
                margin: 5px;
                margin-top: 20px;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                overflow-x: hidden;
            }

            .main-content > div {
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                overflow-x: hidden;
            }

            body {
                overflow-x: hidden !important;
                width: 100% !important;
                max-width: 100vw !important;
            }

            html {
                overflow-x: hidden !important;
                width: 100% !important;
                max-width: 100vw !important;
            }

            .dashboard-header,
            .stats-grid,
            .quick-actions,
            .stat-card,
            .large-stat-card {
                max-width: 100%;
                box-sizing: border-box;
            }

            @media (max-width: 768px) {
                .main-content > div {
                    padding: 0 1rem !important;
                }
            }

            @media (max-width: 640px) {
                .main-content > div {
                    padding: 0 0.75rem !important;
                }
            }

            @media (max-width: 480px) {
                .main-content > div {
                    padding: 0 0.5rem !important;
                }
            }
            
            .username-badge {
                background: rgba(25, 57, 72, 0.08);
                backdrop-filter: blur(10px);
                padding: 0.3rem 0rem 0.3rem 0.7rem;
                border-radius: 2rem;
                border: 2px solid #193948;
                color: #193948;
                font-weight: 700;
                font-size: 1rem;
                white-space: nowrap;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                height: 46px;
                margin: 0;
                transition: all 0.3s ease;
                text-decoration: none;
            }
            
            .username-badge:hover {
                background: rgba(214, 191, 191, 0.25);
                border-color: #D6BFBF;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(214, 191, 191, 0.4), 0 0 20px rgba(214, 191, 191, 0.3);
            }
            
            .username-profile-photo {
                width: 43px;
                height: 43px;
                border-radius: 50%;
                border: none;
                overflow: hidden;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background-color: #F3EBDD;
                color: #193948;
                font-weight: 700;
                font-size: 1.4rem;
                flex-shrink: 0;
                margin: -2px 0 -2px 0;
                transition: all 0.3s ease;
            }

            .username-badge:hover .username-profile-photo {
                box-shadow: 0 0 15px rgba(214, 191, 191, 0.6), 0 0 25px rgba(214, 191, 191, 0.4);
            }
            
            .username-profile-photo img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: all 0.3s ease;
            }

            @media (max-width: 768px) {
                .site-logo-text {
                    font-size: 1.5rem;
                }

                .page-title-badge {
                    font-size: 1rem;
                }


                .notification-icon, .profile-button {
                    width: 36px;
                    height: 36px;
                }

                /* Hide drawer toggle from left side on mobile */
                .drawer-toggle-left {
                    display: none !important;
                }

                /* Show drawer toggle on right side on mobile */
                .drawer-toggle-right {
                    display: inline-flex !important;
                }

                /* Resize drawer toggle to match notification icon size on mobile */
                .drawer-toggle-right {
                    width: 36px !important;
                    height: 36px !important;
                    min-width: 36px !important;
                    min-height: 36px !important;
                    max-width: 36px !important;
                    max-height: 36px !important;
                    border-radius: 50% !important;
                    overflow: hidden !important;
                }

                .drawer-toggle-right svg,
                .drawer-toggle-right .drawer-icon-right {
                    width: 18px !important;
                    height: 18px !important;
                }

                /* Reorder header container - use normal flex direction */
                .header-main-container {
                    justify-content: flex-start !important;
                    flex-wrap: nowrap;
                    gap: 0.5rem;
                }

                /* Left items group (Logo, Site Name) - appears on left */
                .header-left-items {
                    order: 1;
                    display: flex;
                }

                /* Inside left items: Logo (leftmost), Site Name */
                .header-logo-link {
                    order: 1;
                }

                .header-site-name {
                    order: 2;
                }

                /* Right items group - use margin-left: auto to push to right, then reverse internal order */
                .header-right-items {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    order: 2;
                    margin-left: auto;
                    flex-direction: row-reverse;
                }

                /* Inside right items (reversed): Logout=1 (rightmost), Drawer=2, Notifications=3, Complaints=4, Profile=5 (leftmost of group) */
                /* With row-reverse, this gives us the order from right to left as requested */
                .header-logout-btn {
                    order: 1;
                }

                .drawer-toggle-right {
                    order: 2;
                }

                .header-notifications-btn {
                    order: 3;
                }

                .header-complaints-btn {
                    order: 4;
                }

                .header-profile-btn {
                    order: 5;
                }

                .header-button {
                    padding: 0.4rem 1rem;
                    font-size: 0.9rem;
                }
                
                .username-badge {
                    font-size: 0.9rem;
                    padding: 0.25rem 0rem 0.25rem 0.6rem;
                    gap: 0.4rem;
                    height: 40px;
                }
                
                .username-profile-photo {
                    width: 40px;
                    height: 40px;
                    font-size: 0.85rem;
                    margin: -2px -2px -2px 0;
                }
            }

            @media (max-width: 640px) {
                .site-logo-text {
                    font-size: 1.2rem;
                }

                .page-title-badge {
                    font-size: 0.9rem;
                }

                /* Hide drawer toggle from left side */
                .drawer-toggle-left {
                    display: none !important;
                }

                /* Show drawer toggle on right side */
                .drawer-toggle-right {
                    display: inline-flex !important;
                }

                /* Resize drawer toggle to match notification icon size */
                .drawer-toggle-right {
                    width: 35px !important;
                    height: 35px !important;
                    min-width: 35px !important;
                    min-height: 35px !important;
                    max-width: 35px !important;
                    max-height: 35px !important;
                    border-radius: 50% !important;
                    overflow: hidden !important;
                }

                .drawer-toggle-right svg,
                .drawer-toggle-right .drawer-icon-right {
                    width: 18px !important;
                    height: 18px !important;
                }

                /* Same reorder as 768px */
                .header-main-container {
                    justify-content: flex-start !important;
                    flex-wrap: nowrap;
                    gap: 0.5rem;
                }

                .header-left-items {
                    order: 1;
                    display: flex;
                }

                .header-logo-link {
                    order: 1;
                }

                .header-site-name {
                    order: 2;
                }

                .header-right-items {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    order: 2;
                    margin-left: auto;
                    flex-direction: row-reverse;
                }

                .header-logout-btn {
                    order: 1;
                }

                .drawer-toggle-right {
                    order: 2;
                }

                .header-notifications-btn {
                    order: 3;
                }

                .header-complaints-btn {
                    order: 4;
                }

                .header-profile-btn {
                    order: 5;
                }
                
                .username-badge {
                    font-size: 0.8rem;
                    padding: 0.2rem 0rem 0.2rem 0.5rem;
                    gap: 0.3rem;
                    height: 36px;
                }
                
                .username-profile-photo {
                    width: 32px;
                    height: 32px;
                    font-size: 0.75rem;
                    margin: -2px -2px -2px 0;
                }
            }

            /* Drawer Styles */
            .drawer-toggle {
                width: 42px;
                height: 42px;
                min-width: 42px;
                min-height: 42px;
                max-width: 42px;
                max-height: 42px;
                border-radius: 50% !important;
                background-color: #193948;
                color: #F3EBDD;
                border: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 8px rgba(25, 57, 72, 0.3);
                position: relative;
                transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
                flex-shrink: 0;
                overflow: hidden;
                box-sizing: border-box;
            }

            /* Hide right drawer toggle on desktop */
            .drawer-toggle-right {
                display: none;
            }

            .drawer-toggle:hover:not(.drawer-open) {
                transform: scale(1.15);
                box-shadow: 0 4px 12px rgba(214, 191, 191, 0.4);
                background-color: #D6BFBF;
            }

            .drawer-toggle.drawer-open {
                z-index: 1000;
                position: relative;
                transform: rotate(00deg) scale(1.15);
                box-shadow: 0 4px 12px rgba(214, 191, 191, 0.4);
                background-color: #D6BFBF;
                animation: glow 2s ease-in-out infinite;
            }

            .drawer-toggle.drawer-open:hover {
                transform: rotate(45deg) scale(1.2);
            }

            .drawer-toggle svg {
                width: 24px;
                height: 24px;
                flex-shrink: 0;
            }

            .drawer-toggle.drawer-open svg,
            .drawer-toggle.drawer-open .drawer-icon-right {
                transform: rotate(-90deg);
            }

            @keyframes glow {
                0%, 100% {
                    box-shadow: 0 4px 12px rgba(214, 191, 191, 0.4);
                }
                50% {
                    box-shadow: 0 4px 20px rgba(214, 191, 191, 0.7), 0 0 30px rgba(214, 191, 191, 0.3);
                }
            }


            .drawer-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(54, 69, 79, 0.7);
                z-index: 998;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.25s ease, visibility 0.25s ease;
                backdrop-filter: blur(4px);
                will-change: opacity;
            }

            .drawer-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            .drawer {
                position: fixed;
                top: 0;
                left: 0;
                width: 322px;
                max-width: 85vw;
                height: 100%;
                background: linear-gradient(180deg, #F3EBDD 0%, #e8ddd0 100%);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
                z-index: 999;
                transform: translateX(-100%);
                transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                overflow-y: auto;
                overflow-x: hidden;
                display: flex;
                flex-direction: column;
                padding: 0 1rem;
                will-change: transform;
            }

            /* Custom Scrollbar */
            .drawer::-webkit-scrollbar {
                width: 10px;
            }

            .drawer::-webkit-scrollbar-track {
                background: rgba(25, 57, 72, 0.1);
                border-radius: 10px;
                margin: 10px 0;
            }

            .drawer::-webkit-scrollbar-thumb {
                background: linear-gradient(180deg, #193948 0%, #2a4a5a 100%);
                border-radius: 10px;
                border: 2px solid rgba(243, 235, 221, 0.3);
                transition: all 0.3s ease;
            }

            .drawer::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(180deg, #D6BFBF 0%, #4FADC0 100%);
                box-shadow: 0 2px 8px rgba(214, 191, 191, 0.4);
            }

            /* Firefox Scrollbar */
            .drawer {
                scrollbar-width: thin;
                scrollbar-color: #193948 rgba(25, 57, 72, 0.1);
            }

            .drawer.active {
                transform: translateX(0);
            }

            .drawer-header {
                padding: 2rem 1rem;
                border-bottom: 3px solid #193948;
                background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
                color: #F3EBDD;
                position: relative;
                margin: 0 -1rem;
            }

            .drawer-profile-section {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
            }

            .drawer-user-info {
                flex: 1;
                text-align: center;
            }

            .drawer-user-name {
                font-size: 1.35rem;
                font-weight: 700;
                color: #F3EBDD;
                margin-bottom: 0.5rem;
                cursor: pointer;
                transition: all 0.3s ease;
                display: inline-block;
            }

            .drawer-user-name:hover {
                transform: scale(1.1);
                text-shadow: 0 0 10px rgba(214, 191, 191, 0.8), 0 0 20px rgba(214, 191, 191, 0.5);
            }

            .drawer-user-role {
                font-size: 0.75rem;
                color: #D6BFBF;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-weight: 600;
                animation: roleGlow 2s ease-in-out infinite;
            }

            @keyframes roleGlow {
                0%, 100% {
                    text-shadow: 0 0 5px rgba(214, 191, 191, 0.5);
                }
                50% {
                    text-shadow: 0 0 15px rgba(214, 191, 191, 0.9), 0 0 25px rgba(214, 191, 191, 0.6);
                }
            }

            .drawer-profile-photo {
                width: 105px;
                height: 105px;
                border-radius: 50%;
                border: 4px solid #F3EBDD;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #F3EBDD;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
                flex-shrink: 0;
                cursor: pointer;
            }

            .drawer-profile-photo:hover {
                transform: scale(1.15);
                box-shadow: 0 6px 20px rgba(214, 191, 191, 0.6), 0 0 30px rgba(214, 191, 191, 0.4);
                border-color: #D6BFBF;
            }

            .drawer-profile-link {
                text-decoration: none;
                color: inherit;
            }

            .drawer-profile-photo img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .drawer-content {
                flex: 1;
                padding: 1.5rem 1rem;
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .drawer-menu-title {
                font-size: 0.85rem;
                font-weight: 700;
                color: #193948;
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-bottom: 0.5rem;
                padding: 0 0.5rem;
            }

            .drawer-menu-items {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .drawer-menu-item {
                background-color: #D6BFBF;
                color: #193948;
                padding: 1rem 1.25rem;
                border-radius: 0.75rem;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                box-shadow: 0 2px 6px rgba(214, 191, 191, 0.3);
                border: 2px solid transparent;
            }

            .drawer-menu-item:hover {
                background-color: #4FADC0;
                color: #193948;
                transform: translateX(8px) scale(1.02);
                box-shadow: 0 4px 12px rgba(79, 173, 192, 0.4);
                border: 2px solid transparent;
            }

            .drawer-logout-section {
                padding: 1.5rem 1rem;
                border-top: 3px solid #193948;
                background: linear-gradient(180deg, #e8ddd0 0%, #F3EBDD 100%);
                margin: 0 -1rem;
            }

            .drawer-logout-button {
                width: 100%;
                background-color: #E76268;
                color: white;
                padding: 1rem 1.25rem;
                border-radius: 0.75rem;
                text-decoration: none;
                font-weight: 700;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                box-shadow: 0 2px 8px rgba(231, 98, 104, 0.3);
                border: 2px solid transparent;
                cursor: pointer;
                border: none;
                font-size: 1rem;
            }

            .drawer-logout-button:hover {
                background-color: #c54d52;
                transform: scale(1.05);
                box-shadow: 0 4px 16px rgba(231, 98, 104, 0.5);
                border-color: #193948;
            }

            @media (max-width: 640px) {
                .drawer {
                    width: 300px;
                    padding: 0 0.75rem;
                }

                .drawer-profile-photo {
                    width: 95px;
                    height: 95px;
                }

                .drawer-user-name {
                    font-size: 1.15rem;
                }

                .drawer-user-role {
                    font-size: 0.7rem;
                }

                .drawer-menu-item {
                    padding: 0.875rem 1rem;
                    font-size: 0.9rem;
                }
            }

            /* Page Title Section Responsive Styles */
            /* Hide page title in mobile/tablet, show only on desktop */
            @media (max-width: 1024px) {
                .page-title-section {
                    display: none !important;
                }
            }

            /* Show page title only on desktop (above 1024px) */
            @media (min-width: 1025px) {
                .page-title-section {
                    display: flex;
                }
            }

            /* Header Responsive Styles */
            @media (max-width: 1024px) {
                .responsive-header {
                    padding: 0.5rem 1.5rem !important;
                }
            }

            /* في الكمبيوترات - اسم الصفحة يبقى كما هو (absolute) */

            @media (max-width: 768px) {
                .responsive-header {
                    padding: 0.4rem 1rem !important;
                    min-height: 60px !important;
                }

                .site-logo-text {
                    font-size: 1.3rem !important;
                }

                .username-badge {
                    font-size: 0.85rem !important;
                    padding: 0.2rem 0.5rem 0.2rem 0.5rem !important;
                    gap: 0.3rem !important;
                    height: 38px !important;
                }

                .username-profile-photo {
                    width: 35px !important;
                    height: 35px !important;
                    font-size: 1.2rem !important;
                    margin: -1px 0 -1px -1px !important;
                }

                .notification-icon, .profile-button {
                    width: 35px !important;
                    height: 35px !important;
                }
            }

            @media (max-width: 640px) {
                .responsive-header {
                    padding: 0.3rem 0.75rem !important;
                    min-height: 55px !important;
                }

                .site-logo-text {
                    font-size: 1.1rem !important;
                }


                .username-badge {
                    font-size: 0.8rem !important;
                    padding: 0.15rem 0.4rem 0.15rem 0.4rem !important;
                    gap: 0.25rem !important;
                    height: 34px !important;
                }

                .username-profile-photo {
                    width: 30px !important;
                    height: 30px !important;
                    font-size: 1rem !important;
                    margin: -1px -1px -1px -1px !important;
                }

                .notification-icon {
                    width: 32px !important;
                    height: 32px !important;
                }
            }

            /* Footer Styles */
            .footer {
                background: #36454f;
                color: #F3EBDD;
                padding: 3rem 1rem;
                margin-top: 3rem;
                border-top: 4px solid #193948;
            }

            .footer-content {
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                gap: 2rem;
            }

            .footer-top {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: center;
                gap: 0;
            }

            .footer-logo-section {
                display: flex;
                align-items: center;
                gap: 0;
            }

            .footer-actions {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 0.75rem;
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
                box-shadow: 0 0 30px rgba(243, 235, 221, 0.7), 0 0 60px rgba(214, 191, 191, 0.5), inset 0 0 20px rgba(243, 235, 221, 0.4);
            }

            .footer-text-section {
                flex: 1;
                text-align: left;
                margin-left: 0;
                padding-left: 0;
            }

            .footer-text {
                font-size: 1rem;
                opacity: 0.9;
                margin: 0.25rem 0;
            }

            .footer-links {
                display: flex;
                flex-wrap: wrap;
                gap: 0.4rem;
                justify-content: flex-end;
                align-items: center;
                margin-top: 0;
            }

            .footer-link {
                color: #F3EBDD;
                text-decoration: none;
                padding: 0.5rem 0.75rem;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
                font-weight: 500;
                font-size: 0.9rem;
            }

            .footer-link:hover {
                background-color: #193948;
                transform: translateY(-2px);
            }

            .footer-social {
                display: flex;
                gap: 0.75rem;
                justify-content: flex-end;
                align-items: center;
                margin-top: 0;
            }

            .footer-social-link {
                width: 45px;
                height: 45px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #193948;
                color: #F3EBDD;
                border-radius: 50%;
                text-decoration: none;
                transition: all 0.3s ease;
                font-size: 1.3rem;
            }

            .footer-social-link:hover {
                background-color: #D6BFBF;
                color: #193948;
                transform: scale(1.1);
            }

            .footer-bottom {
                text-align: center;
                padding-top: 1rem;
                border-top: 1px solid rgba(243, 235, 221, 0.2);
            }

            @media (max-width: 768px) {
                .footer-top {
                    flex-direction: column;
                    text-align: center;
                }

                .footer-logo-section {
                    justify-content: center;
                }

                .footer-links {
                    flex-direction: column;
                    width: 100%;
                }

                .footer-link {
                    width: 100%;
                    text-align: center;
                }
            }

            @media (max-width: 480px) {
                .responsive-header {
                    padding: 0.25rem 0.5rem !important;
                    min-height: 50px !important;
                }

                .flex.items-center.gap-3 {
                    gap: 0.5rem !important;
                }

                .site-logo-text {
                    font-size: 1rem !important;
                }


                /* Hide drawer toggle from left side */
                .drawer-toggle-left {
                    display: none !important;
                }

                /* Show drawer toggle on right side */
                .drawer-toggle-right {
                    display: inline-flex !important;
                }

                /* Resize drawer toggle to match notification icon size */
                .drawer-toggle-right {
                    width: 28px !important;
                    height: 28px !important;
                    min-width: 28px !important;
                    min-height: 28px !important;
                    max-width: 28px !important;
                    max-height: 28px !important;
                    border-radius: 50% !important;
                    overflow: hidden !important;
                }

                .drawer-toggle-right svg,
                .drawer-toggle-right .drawer-icon-right {
                    width: 16px !important;
                    height: 16px !important;
                }

                /* Same reorder as 768px and 640px */
                .header-main-container {
                    justify-content: flex-start !important;
                    flex-wrap: nowrap;
                    gap: 0.5rem;
                }

                .header-left-items {
                    order: 1;
                    display: flex;
                }

                .header-logo-link {
                    order: 1;
                }

                .header-site-name {
                    order: 2;
                }

                .header-right-items {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    order: 2;
                    margin-left: auto;
                    flex-direction: row-reverse;
                }

                .header-logout-btn {
                    order: 1;
                }

                .drawer-toggle-right {
                    order: 2;
                }

                .header-notifications-btn {
                    order: 3;
                }

                .header-complaints-btn {
                    order: 4;
                }

                .header-profile-btn {
                    order: 5;
                }

                .username-badge {
                    font-size: 0.75rem !important;
                    padding: 0.1rem 0.3rem 0.1rem 0.3rem !important;
                    gap: 0.2rem !important;
                    height: 30px !important;
                }

                .username-profile-photo {
                    width: 26px !important;
                    height: 26px !important;
                    font-size: 0.9rem !important;
                    margin: -1px -1px -1px -1px !important;
                }

                .notification-icon {
                    width: 28px !important;
                    height: 28px !important;
                }
            }

            /* Compact, modern layout for non-dashboard/non-notification pages */
            .main-content.main-content--compact {
                padding-inline: clamp(0.65rem, 1.8vw, 1.6rem);
            }

            .main-content.main-content--compact > .max-w-7xl {
                max-width: 1120px !important;
                margin-inline: auto;
                padding-inline: clamp(0.5rem, 1.4vw, 1rem) !important;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .main-content.main-content--compact > .max-w-7xl > * {
                width: min(100%, 1000px);
                margin-left: auto !important;
                margin-right: auto !important;
            }

            .main-content.main-content--compact .page-container,
            .main-content.main-content--compact .create-artwork-form {
                max-width: 1000px;
                margin-inline: auto !important;
                border-radius: 1rem;
                box-shadow: 0 8px 22px rgba(25, 57, 72, 0.12);
            }

            .main-content.main-content--compact form {
                max-width: 980px;
            }

            .main-content.main-content--compact .form-input,
            .main-content.main-content--compact .form-input-custom,
            .main-content.main-content--compact input[type="file"] {
                min-height: 46px;
                border-radius: 0.6rem;
            }

            .main-content.main-content--compact textarea.form-input,
            .main-content.main-content--compact textarea.form-input-custom,
            .main-content.main-content--compact textarea {
                min-height: 120px;
                resize: vertical;
            }

            .main-content.main-content--compact h1,
            .main-content.main-content--compact h2 {
                font-size: clamp(1.35rem, 1.2rem + 0.8vw, 1.9rem) !important;
                line-height: 1.25;
            }

            .main-content.main-content--compact h3 {
                font-size: clamp(1.1rem, 1rem + 0.45vw, 1.35rem) !important;
            }

            @media (max-width: 1024px) {
                .main-content.main-content--compact > .max-w-7xl {
                    padding-inline: 0.65rem !important;
                    align-items: stretch;
                }

                .main-content.main-content--compact > .max-w-7xl > * {
                    width: 100%;
                }

                .main-content.main-content--compact .page-container,
                .main-content.main-content--compact .create-artwork-form {
                    max-width: 100%;
                }
            }

            @media (max-width: 640px) {
                .main-content.main-content--compact .form-input,
                .main-content.main-content--compact .form-input-custom,
                .main-content.main-content--compact input[type="file"] {
                    min-height: 42px;
                }

                .main-content.main-content--compact .page-container,
                .main-content.main-content--compact .create-artwork-form {
                    box-shadow: 0 4px 12px rgba(25, 57, 72, 0.1);
                }
            }

            /* Premium modern complaint/report styling (all roles, logic unchanged) */
            .main-content.main-content--cases > .max-w-7xl {
                max-width: 1220px !important;
            }

            .main-content.main-content--cases > .max-w-7xl > div {
                padding: clamp(0.45rem, 1.3vw, 1rem) !important;
            }

            .main-content.main-content--cases .stat-card,
            .main-content.main-content--cases .page-container,
            .main-content.main-content--cases [class*="rounded-lg"],
            .main-content.main-content--cases [class*="shadow-lg"] {
                background: linear-gradient(180deg, #f5eee3 0%, #efe5d7 100%) !important;
                border: 1px solid rgba(25, 57, 72, 0.14) !important;
                border-radius: 1.15rem !important;
                box-shadow: 0 10px 30px rgba(17, 37, 48, 0.14), inset 0 1px 0 rgba(255, 255, 255, 0.45) !important;
            }

            .main-content.main-content--cases [style*="border: 2px solid #193948"],
            .main-content.main-content--cases [style*="border:2px solid #193948"],
            .main-content.main-content--cases [style*="border: 3px solid #193948"] {
                border-width: 1px !important;
                border-color: rgba(25, 57, 72, 0.18) !important;
                border-radius: 1rem !important;
            }

            .main-content.main-content--cases h2,
            .main-content.main-content--cases h3 {
                color: #193948 !important;
                letter-spacing: 0.01em;
            }

            .main-content.main-content--cases input[type="text"],
            .main-content.main-content--cases input[type="email"],
            .main-content.main-content--cases input[type="url"],
            .main-content.main-content--cases input[type="number"],
            .main-content.main-content--cases input[type="file"],
            .main-content.main-content--cases input[type="date"],
            .main-content.main-content--cases select,
            .main-content.main-content--cases textarea {
                width: 100%;
                min-height: 46px;
                border: 1.5px solid rgba(25, 57, 72, 0.25) !important;
                border-radius: 0.85rem !important;
                background: #ffffff !important;
                color: #193948 !important;
                padding: 0.72rem 0.92rem !important;
                box-shadow: inset 0 1px 1px rgba(25, 57, 72, 0.06);
                transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
            }

            .main-content.main-content--cases textarea {
                min-height: 142px;
                resize: vertical;
            }

            .main-content.main-content--cases input:focus,
            .main-content.main-content--cases select:focus,
            .main-content.main-content--cases textarea:focus {
                outline: none;
                border-color: #4FADC0 !important;
                box-shadow: 0 0 0 4px rgba(79, 173, 192, 0.2), inset 0 1px 1px rgba(25, 57, 72, 0.05);
                transform: translateY(-1px);
            }

            .main-content.main-content--cases .primary-button,
            .main-content.main-content--cases .secondary-button,
            .main-content.main-content--cases button[type="submit"],
            .main-content.main-content--cases a[class*="button"] {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                text-align: center !important;
                line-height: 1.2 !important;
                vertical-align: middle !important;
                border-radius: 10px !important;
                font-weight: 700 !important;
                letter-spacing: 0.012em;
                padding: 0.64rem 1.12rem !important;
                border: 1px solid transparent !important;
                box-shadow: 0 6px 16px rgba(25, 57, 72, 0.18);
                transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
            }

            .main-content.main-content--cases .primary-button:hover,
            .main-content.main-content--cases .secondary-button:hover,
            .main-content.main-content--cases button[type="submit"]:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(25, 57, 72, 0.2);
                filter: saturate(1.05);
            }

            .main-content.main-content--cases [style*="border-bottom: 3px solid"] {
                border-bottom-width: 2px !important;
                border-radius: 0.6rem !important;
            }

            .main-content.main-content--cases table {
                width: 100% !important;
                border-collapse: separate !important;
                border-spacing: 0 !important;
                border-radius: 1.05rem !important;
                overflow: hidden;
                border: 1px solid rgba(25, 57, 72, 0.14) !important;
                box-shadow: 0 10px 24px rgba(17, 37, 48, 0.12);
            }

            .main-content.main-content--cases table thead tr {
                background: linear-gradient(90deg, #163645 0%, #1f4658 100%) !important;
            }

            .main-content.main-content--cases table th {
                color: #f2e9dc !important;
                font-size: 0.79rem !important;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                padding: 0.88rem 0.7rem !important;
                border-right: 1px solid rgba(79, 173, 192, 0.2) !important;
            }

            .main-content.main-content--cases table th:last-child {
                border-right: none !important;
            }

            .main-content.main-content--cases table td {
                background: #fff;
                color: #193948 !important;
                border-bottom: 1px solid rgba(25, 57, 72, 0.08) !important;
                border-right: 1px solid rgba(25, 57, 72, 0.07) !important;
                padding: 0.88rem 0.72rem !important;
                vertical-align: middle;
            }

            .main-content.main-content--cases table td:last-child {
                border-right: none !important;
            }

            .main-content.main-content--cases table tbody tr:nth-child(even) td {
                background: #fcfaf6;
            }

            .main-content.main-content--cases table tbody tr:hover td {
                background: #f7efe3 !important;
            }

            .main-content.main-content--cases span[style*="border-radius: 20px"],
            .main-content.main-content--cases span[style*="border-radius:20px"],
            .main-content.main-content--cases .status-badge {
                border-radius: 999px !important;
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                gap: 0.28rem;
                padding: 0.32rem 0.72rem !important;
                line-height: 1.1;
                white-space: nowrap;
                font-weight: 700 !important;
                box-shadow: 0 3px 10px rgba(25, 57, 72, 0.14);
            }

            .main-content.main-content--cases [style*="overflow-x: auto"] {
                border-radius: 1rem !important;
            }

            @media (max-width: 768px) {
                .main-content.main-content--cases > .max-w-7xl {
                    max-width: 100% !important;
                }

                .main-content.main-content--cases table th,
                .main-content.main-content--cases table td {
                    padding: 0.68rem 0.5rem !important;
                    font-size: 0.83rem !important;
                }
            }
        </style>
    </head>
    <body>
        <div class="min-h-screen" style="background-color: #36454f;">
            <!-- Header -->
            <header style="background-color: #F3EBDD; padding: 0.5rem 1rem; position: relative; min-height: 70px; display: flex; align-items: center;" class="responsive-header">
                <div class="max-w-7xl mx-auto" style="width: 100%;">
                    <div class="flex justify-between items-center gap-2 sm:gap-4 header-main-container" style="height: 100%;">
                        <!-- Left: Logo + Site Name + Drawer Toggle -->
                        <div class="flex items-center gap-3 header-left-items">
                            @auth
                                @php
                                    $user = Auth::user();
                                    $dashboardRoute = 'dashboard';
                                    if ($user->hasRole('super_admin')) {
                                        $dashboardRoute = 'superadmin.dashboard';
                                    } elseif ($user->hasRole('admin')) {
                                        $dashboardRoute = 'admin.dashboard';
                                    } elseif ($user->hasRole('gestionnaire')) {
                                        $dashboardRoute = 'gestionnaire.dashboard';
                                    } elseif ($user->hasRole('artist')) {
                                        $dashboardRoute = 'artist.dashboard';
                                    } elseif ($user->hasRole('agent')) {
                                        $dashboardRoute = 'agent.dashboard';
                                    }
                                @endphp
                                <a href="{{ route($dashboardRoute) }}" class="header-logo-link" style="display: flex; align-items: stretch; text-decoration: none; position: relative; margin: -0.5rem 0; height: calc(70px); align-self: flex-end;">
                                    <img src="{{ asset('icons/logo.png') }}" alt="ArtRights Logo" style="width: 35px; height: 100%; object-fit: contain;">
                                </a>
                                <a href="{{ route($dashboardRoute) }}" class="site-logo-text header-site-name">
                                    ArtRights
                                </a>
                                <button type="button" class="drawer-toggle drawer-toggle-left" id="drawerToggle" aria-label="Toggle Menu" style="margin-left: 0.50rem;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" id="drawerIcon">
                                        <path d="M3 12H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                        <path d="M3 6H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                        <path d="M3 18H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            @else
                                <a href="/" style="display: flex; align-items: stretch; text-decoration: none; position: relative; margin: -0.5rem 0; height: calc(70px); align-self: flex-end;">
                                    <img src="{{ asset('icons/logo.png') }}" alt="ArtRights Logo" style="width: 35px; height: 100%; object-fit: contain;">
                                </a>
                                <a href="/" class="site-logo-text">
                                    ArtRights
                                </a>
                            @endauth
                        </div>

                        <!-- Right: Username + Profile + Notifications + Logout -->
                        @auth
                            @php
                                $user = Auth::user();
                                $notificationsRoute = null;
                                $routePrefix = 'admin';
                                if ($user->hasRole('super_admin')) {
                                    $notificationsRoute = route('superadmin.notifications');
                                    $routePrefix = 'superadmin';
                                } elseif ($user->hasRole('admin')) {
                                    $notificationsRoute = route('admin.notifications');
                                    $routePrefix = 'admin';
                                } elseif ($user->hasRole('gestionnaire')) {
                                    $notificationsRoute = route('gestionnaire.notifications');
                                    $routePrefix = 'gestionnaire';
                                } elseif ($user->hasRole('artist')) {
                                    $notificationsRoute = route('artist.notifications');
                                    $routePrefix = 'artist';
                                } elseif ($user->hasRole('agent')) {
                                    $notificationsRoute = route('agent.notifications');
                                    $routePrefix = 'agent';
                                }
                                $unreadNotifications = $user?->notifications()->where('is_read', false)->count() ?? 0;
                                $recentNotifications = $user?->notifications()->latest()->limit(6)->get() ?? collect();
                                
                                // Complaints route for Super Admin, Admin, and Gestionnaire
                                $complaintsRoute = null;
                                if ($user->hasRole('super_admin')) {
                                    $complaintsRoute = route('superadmin.complaints.index');
                                } elseif ($user->hasRole('admin')) {
                                    $complaintsRoute = route('admin.complaints.index');
                                } elseif ($user->hasRole('gestionnaire')) {
                                    $complaintsRoute = route('gestionnaire.reports-and-complaints.index');
                                } elseif ($user->hasRole('artist')) {
                                    $complaintsRoute = route('artist.complaints.index');
                                } elseif ($user->hasRole('agent')) {
                                    $complaintsRoute = route('agent.complaints.index');
                                }
                                
                                // All users now have profile button
                                $profileRoute = null;
                                $profilePhotoUrl = $user->profile_photo_url;
                                
                                if ($user->hasRole('super_admin')) {
                                    $profileRoute = route('superadmin.profile');
                                } elseif ($user->hasRole('admin')) {
                                    $profileRoute = route('admin.profile');
                                } elseif ($user->hasRole('gestionnaire')) {
                                    $profileRoute = route('gestionnaire.profile');
                                } elseif ($user->hasRole('artist')) {
                                    $profileRoute = route('artist.profile');
                                } elseif ($user->hasRole('agent')) {
                                    $profileRoute = route('agent.profile');
                                }
                            @endphp
                            
                            <div class="flex items-center header-right-items" style="gap: 0.5rem;">
                                @if($profileRoute)
                                    <a href="{{ $profileRoute }}" class="username-badge header-profile-btn" aria-label="Profile and Username">
                                        <span>{{ $user?->name }}</span>
                                        <div class="username-profile-photo">
                                            @if($profilePhotoUrl)
                                                <img src="{{ $profilePhotoUrl }}" alt="Profile photo">
                                            @else
                                                👤
                                            @endif
                                        </div>
                                    </a>
                                @else
                                    <div class="username-badge header-profile-btn" style="cursor: default;">
                                        <span>{{ $user?->name }}</span>
                                        <div class="username-profile-photo">
                                            @if($user && isset($user->profile_photo_url) && $user->profile_photo_url)
                                                <img src="{{ $user->profile_photo_url }}" alt="Profile photo">
                                            @else
                                                👤
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Drawer Toggle Button - Will be repositioned on mobile -->
                                <button type="button" class="drawer-toggle drawer-toggle-right" id="drawerToggleRight" aria-label="Toggle Menu" style="display: none;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="drawer-icon-right">
                                        <path d="M3 12H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                        <path d="M3 6H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                        <path d="M3 18H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                    </svg>
                                </button>

                                @if($complaintsRoute)
                                    <a href="{{ $complaintsRoute }}" class="notification-icon header-complaints-btn" aria-label="{{ $user->hasRole('artist') ? 'Complaints' : 'Reports and Complaints' }}" title="{{ $user->hasRole('artist') ? 'Complaints' : 'Reports and Complaints' }}">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.89 22 5.99 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16 13H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16 17H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10 9H9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                @endif

                                @if($notificationsRoute)
                                    <div class="notifications-menu-container header-notifications-btn" id="notificationsMenuContainer">
                                        <button type="button" class="notification-icon" id="notificationsToggle" aria-label="Notifications" title="Notifications" aria-expanded="false">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            @if($unreadNotifications > 0)
                                                <span class="notification-badge">
                                                    {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                                                </span>
                                            @endif
                                        </button>

                                        <div class="notifications-dropdown" id="notificationsDropdown">
                                            <div class="notifications-dropdown-header">
                                                <span>Notifications</span>
                                                <span>{{ $unreadNotifications }} unread</span>
                                            </div>

                                            <div class="notifications-dropdown-list">
                                                @forelse($recentNotifications as $notification)
                                                    <div class="notifications-dropdown-item {{ $notification->is_read ? '' : 'unread' }}">
                                                        <form method="POST" action="{{ route($routePrefix . '.notifications.delete', $notification->id) }}" style="margin: 0;" onsubmit="event.stopPropagation(); return confirm('Delete this notification?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="notifications-dropdown-delete" title="Delete notification">×</button>
                                                        </form>
                                                        <a href="{{ route($routePrefix . '.notifications.view', $notification->id) }}" style="display:block; text-decoration:none; color:inherit;">
                                                            <div class="notifications-dropdown-item-title">
                                                                @if(!$notification->is_read) • @endif
                                                                {{ \Illuminate\Support\Str::limit($notification->title, 48) }}
                                                            </div>
                                                            <div class="notifications-dropdown-item-message">
                                                                {{ \Illuminate\Support\Str::limit($notification->message, 88) }}
                                                            </div>
                                                            <div class="notifications-dropdown-item-time">
                                                                {{ $notification->created_at?->diffForHumans() }}
                                                            </div>
                                                        </a>
                                                    </div>
                                                @empty
                                                    <div class="notifications-dropdown-empty">
                                                        No notifications yet.
                                                    </div>
                                                @endforelse
                                            </div>

                                            <div class="notifications-dropdown-actions">
                                                <form method="POST" action="{{ route($routePrefix . '.notifications.mark-all-read') }}" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" class="notifications-dropdown-btn primary" {{ $unreadNotifications < 1 ? 'disabled' : '' }}>
                                                        Mark all read
                                                    </button>
                                                </form>
                                                <a href="{{ $notificationsRoute }}" class="notifications-dropdown-btn secondary">
                                                    Open all
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('logout') }}" class="inline header-logout-btn">
                                    @csrf
                                    <button type="submit" class="notification-icon" aria-label="Logout" title="Logout" style="font-size: 1.4rem;">
                                        ⏻
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>

            </header>

            <!-- Page Title Section - Separate from Header -->
            @auth
                <div class="page-title-section">
                    <div class="page-title-badge">
                        {{ $pageTitle ?? 'Dashboard' }}
                        @php
                            $user = Auth::user();
                            $agencyName = null;
                            if ($user->hasRole('admin') || $user->hasRole('gestionnaire') || $user->hasRole('agent')) {
                                $agencyName = $user->artist?->agency?->name ?? $user->agent?->agency?->name ?? $user->gestionnaire?->agency?->name;
                            }
                        @endphp
                        @if($agencyName)
                            <span style="color: #D6BFBF; margin-left: 0.5rem;">
                                - {{ $agencyName }}
                            </span>
                        @endif
                    </div>
                </div>
            @endauth

            @auth
                <!-- Drawer Overlay -->
                <div class="drawer-overlay" id="drawerOverlay"></div>

                <!-- Drawer -->
                <div class="drawer" id="drawer">
                    <div class="drawer-header">
                        <div class="drawer-profile-section">
                            <div class="drawer-user-info">
                                @php
                                    $profileRoute = null;
                                    if ($user->hasRole('super_admin')) {
                                        $profileRoute = route('superadmin.profile');
                                    } elseif ($user->hasRole('admin')) {
                                        $profileRoute = route('admin.profile');
                                    } elseif ($user->hasRole('gestionnaire')) {
                                        $profileRoute = route('gestionnaire.profile');
                                    } elseif ($user->hasRole('artist')) {
                                        $profileRoute = route('artist.profile');
                                    } elseif ($user->hasRole('agent')) {
                                        $profileRoute = route('agent.profile');
                                    }
                                @endphp
                                @if($profileRoute)
                                    <a href="{{ $profileRoute }}" class="drawer-profile-link">
                                        <div class="drawer-user-name">{{ $user->name }}</div>
                                    </a>
                                @else
                                    <div class="drawer-user-name">{{ $user->name }}</div>
                                @endif
                                <div class="drawer-user-role">
                                    @php
                                        $roleName = 'User';
                                        if ($user->hasRole('super_admin')) {
                                            $roleName = 'Super Admin';
                                        } elseif ($user->hasRole('admin')) {
                                            $roleName = 'Admin';
                                        } elseif ($user->hasRole('gestionnaire')) {
                                            $roleName = 'Gestionnaire';
                                        } elseif ($user->hasRole('artist')) {
                                            $roleName = 'Artist';
                                        } elseif ($user->hasRole('agent')) {
                                            $roleName = 'Agent';
                                        }
                                    @endphp
                                    {{ $roleName }}
                                </div>
                            </div>
                            @if($profileRoute)
                                <a href="{{ $profileRoute }}" class="drawer-profile-link">
                                    <div class="drawer-profile-photo">
                                        @php
                                            $profilePhotoUrl = $user->profile_photo_url;
                                        @endphp
                                        @if($profilePhotoUrl)
                                            <img src="{{ $profilePhotoUrl }}" alt="Profile photo">
                                        @else
                                            <span style="font-size: 2.5rem;">👤</span>
                                        @endif
                                    </div>
                                </a>
                            @else
                                <div class="drawer-profile-photo">
                                    @php
                                        $profilePhotoUrl = $user->profile_photo_url;
                                    @endphp
                                    @if($profilePhotoUrl)
                                        <img src="{{ $profilePhotoUrl }}" alt="Profile photo">
                                    @else
                                        <span style="font-size: 2.5rem;">👤</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="drawer-content">
                        <div class="drawer-menu-title">Dashboard Menu</div>
                        <div class="drawer-menu-items">
                            @php
                                $dashboardButtons = [];
                                if ($user->hasRole('super_admin')) {
                                    $dashboardButtons = [
                                        ['route' => 'superadmin.manage-agencies', 'label' => 'Agencies'],
                                        ['route' => 'superadmin.manage-categories', 'label' => 'Categories'],
                                        ['route' => 'superadmin.manage-transfer-workers', 'label' => 'Transfer Workers'],
                                        ['route' => 'superadmin.manage-device-types', 'label' => 'Devices and Amounts'],
                                        ['route' => 'superadmin.manage-pvs', 'label' => 'PVs'],
                                        ['route' => 'superadmin.manage-law', 'label' => 'Manage Law Content'],
                                        ['route' => 'superadmin.footer-settings', 'label' => 'Footer Settings'],
                                    ];
                                } elseif ($user->hasRole('admin')) {
                                    $dashboardButtons = [
                                        ['route' => 'admin.manage-users', 'label' => 'Users Waiting Approval'],
                                        ['route' => 'admin.complaints.index', 'label' => 'Reports and Complaints'],
                                        ['route' => 'admin.manage-gestionnaires', 'label' => 'Gestionnaires'],
                                        ['route' => 'admin.manage-agents', 'label' => 'Agents'],
                                        ['route' => 'admin.manage-pvs', 'label' => 'All PVs'],
                                        ['route' => 'admin.manage-missions', 'label' => 'All Missions'],
                                        ['route' => 'admin.financial-transactions', 'label' => 'Agency Wallet Balance'],
                                    ];
                                } elseif ($user->hasRole('gestionnaire')) {
                                    $dashboardButtons = [
                                        ['route' => 'gestionnaire.artworks', 'label' => 'Artworks'],
                                        ['route' => 'gestionnaire.reports-and-complaints.index', 'label' => 'Reports and Complaints'],
                                        ['route' => 'gestionnaire.missions.index', 'label' => 'Missions'],
                                        ['route' => 'gestionnaire.pvs.index', 'label' => 'PVs'],
                                        ['route' => 'gestionnaire.wallet-recharge.index', 'label' => 'Wallet Recharge Requests'],
                                        ['route' => 'gestionnaire.wallet.index', 'label' => 'Agency Wallet Balance'],
                                    ];
                                } elseif ($user->hasRole('artist')) {
                                    $dashboardButtons = [
                                        ['route' => 'artist.profile', 'label' => 'My Profile'],
                                        ['route' => 'artist.wallet', 'label' => 'Wallet'],
                                        ['route' => 'artist.create-artwork', 'label' => 'Add New Artwork'],
                                        ['route' => 'artist.complaints.create', 'label' => 'Submit Complaint'],
                                        ['route' => 'artist.law', 'label' => 'Legal Reference'],
                                    ];
                                } elseif ($user->hasRole('agent')) {
                                    $dashboardButtons = [
                                        ['route' => 'agent.profile', 'label' => 'My Profile'],
                                        ['route' => 'agent.missions.index', 'label' => 'Missions'],
                                        ['route' => 'agent.pvs.create', 'label' => 'New PV'],
                                        ['route' => 'agent.law', 'label' => 'Legal Reference'],
                                    ];
                                }
                            @endphp

                            @foreach($dashboardButtons as $button)
                                <a href="{{ route($button['route']) }}" class="drawer-menu-item">
                                    {{ $button['label'] }}
                                </a>
                            @endforeach
                            
                            <!-- Help Menu Item -->
                            <a href="{{ route('help') }}" class="drawer-menu-item">
                                Help Guide
                            </a>
                        </div>
                    </div>

                    <div class="drawer-logout-section">
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="drawer-logout-button">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M16 17l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            @php
                $normalizedPageTitle = strtolower(trim((string) ($pageTitle ?? '')));
                $isDashboardPage = str_contains($normalizedPageTitle, 'dashboard');
                $isNotificationsPage = str_contains($normalizedPageTitle, 'notification');
                $isPvRoute = request()->routeIs(
                    '*.pvs.*',
                    '*.pv.*',
                    '*/pvs*',
                    '*/pv*'
                );
                $isPvTitle = str_contains($normalizedPageTitle, ' pv')
                    || str_starts_with($normalizedPageTitle, 'pv')
                    || str_contains($normalizedPageTitle, 'pvs');
                $isPvPage = $isPvRoute || $isPvTitle;
                $isSuperAdminPage = request()->routeIs('superadmin.*');
                $isSuperAdminCompactException = request()->routeIs(
                    'superadmin.footer-settings',
                    'superadmin.profile'
                );
                $isComplaintOrReportPage = request()->routeIs(
                    '*.complaints.*',
                    '*.reports.*',
                    '*.reports-and-complaints.*',
                    '*.manage-complaints',
                    '*.admin-complaints',
                    '*.superadmin-complaints',
                    '*.view-admin-complaint',
                    '*.view-complaint',
                    '*.create-complaint-or-report'
                );
                $useCompactLayout = ! $isDashboardPage
                    && ! $isNotificationsPage
                    && ! $isPvPage
                    && (! $isSuperAdminPage || $isSuperAdminCompactException);
            @endphp

            <!-- Page Content -->
            <main class="main-content {{ $useCompactLayout ? 'main-content--compact' : '' }} {{ $isComplaintOrReportPage ? 'main-content--cases' : '' }}">
                <div class="max-w-7xl mx-auto" style="padding: 0 clamp(1rem, 2vw, 2rem); width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            @php
                $footerSettings = \App\Models\FooterSetting::getSettings();
            @endphp
            <footer class="footer">
                <div class="footer-content">
                    <div class="footer-top">
                        <div style="display: flex; align-items: center; gap: 0; margin: 0; padding: 0;">
                            @php
                                $footerLogoUrl = null;
                                if (!empty($footerSettings->logo_path)) {
                                    $normalizedLogoPath = ltrim($footerSettings->logo_path, '/');
                                    $logoFullPath = storage_path('app/public/' . $normalizedLogoPath);
                                    if (file_exists($logoFullPath)) {
                                        $footerLogoUrl = route('media.show', ['path' => $normalizedLogoPath]);
                                    }
                                }
                            @endphp
                            @if($footerLogoUrl)
                                <div class="footer-logo-section" style="margin: 0; padding: 0;">
                                    <a href="{{ $footerSettings->ayrade_url ?? $footerSettings->website_url ?? url('/') }}" target="_blank" style="margin: 0; padding: 0; display: block;">
                                        <img src="{{ $footerLogoUrl }}" alt="Ayrade Logo" class="footer-logo" style="margin: 0; display: block;">
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

        @auth
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const drawerToggleLeft = document.getElementById('drawerToggle');
                    const drawerToggleRight = document.getElementById('drawerToggleRight');
                    const drawer = document.getElementById('drawer');
                    const drawerOverlay = document.getElementById('drawerOverlay');
                    const notificationsContainer = document.getElementById('notificationsMenuContainer');
                    const notificationsToggle = document.getElementById('notificationsToggle');
                    const notificationsDropdown = document.getElementById('notificationsDropdown');
                    let isOpen = false;

                    if ((!drawerToggleLeft && !drawerToggleRight) || !drawer || !drawerOverlay) {
                        return;
                    }

                    function toggleDrawer() {
                        isOpen = !isOpen;
                        if (isOpen) {
                            drawer.classList.add('active');
                            drawerOverlay.classList.add('active');
                            if (drawerToggleLeft) drawerToggleLeft.classList.add('drawer-open');
                            if (drawerToggleRight) drawerToggleRight.classList.add('drawer-open');
                            document.body.style.overflow = 'hidden';
                        } else {
                            drawer.classList.remove('active');
                            drawerOverlay.classList.remove('active');
                            if (drawerToggleLeft) drawerToggleLeft.classList.remove('drawer-open');
                            if (drawerToggleRight) drawerToggleRight.classList.remove('drawer-open');
                            document.body.style.overflow = '';
                        }
                    }

                    if (drawerToggleLeft) {
                        drawerToggleLeft.addEventListener('click', toggleDrawer);
                    }
                    if (drawerToggleRight) {
                        drawerToggleRight.addEventListener('click', toggleDrawer);
                    }
                    drawerOverlay.addEventListener('click', toggleDrawer);

                    // Close drawer on escape key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && isOpen) {
                            toggleDrawer();
                        }
                    });

                    if (notificationsContainer && notificationsToggle && notificationsDropdown) {
                        const closeNotifications = () => {
                            notificationsDropdown.classList.remove('open');
                            notificationsToggle.setAttribute('aria-expanded', 'false');
                        };

                        notificationsToggle.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const willOpen = !notificationsDropdown.classList.contains('open');
                            closeNotifications();
                            if (willOpen) {
                                notificationsDropdown.classList.add('open');
                                notificationsToggle.setAttribute('aria-expanded', 'true');
                            }
                        });

                        notificationsDropdown.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });

                        document.addEventListener('click', function(e) {
                            if (!notificationsContainer.contains(e.target)) {
                                closeNotifications();
                            }
                        });

                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape') {
                                closeNotifications();
                            }
                        });
                    }
                });
            </script>
        @endauth
        @stack('scripts')
    </body>
</html>

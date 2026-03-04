<x-allthepages-layout pageTitle="Artist Dashboard">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden !important;
            width: 100% !important;
            max-width: 100vw !important;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(79, 173, 192, 0.3);
            }
            50% {
                box-shadow: 0 0 30px rgba(79, 173, 192, 0.6);
            }
        }

        .dashboard-header {
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            border-radius: 1.25rem;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            margin-top: -2rem;
            box-shadow: 0 8px 24px rgba(25, 57, 72, 0.4);
            animation: fadeInUp 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(214, 191, 191, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        .dashboard-header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .dashboard-header-left {
            flex: 1;
        }

        .dashboard-header-right {
            text-align: right;
        }

        .dashboard-title {
            font-size: 2rem;
            font-weight: 800;
            color: #F3EBDD;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        .dashboard-subtitle {
            font-size: 1rem;
            color: #D6BFBF;
            margin-top: 0.5rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .dashboard-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-size: 1rem;
            color: #D6BFBF;
        }

        .dashboard-info-item {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .dashboard-info-label {
            opacity: 0.9;
            font-weight: 500;
        }

        .dashboard-info-value {
            font-weight: 500;
            color: #F3EBDD;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #F3EBDD 0%, #e8ddd0 100%);
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 4px 16px rgba(25, 57, 72, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
            border: 2px solid transparent;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        .stat-card:nth-child(5) { animation-delay: 0.5s; }
        .stat-card:nth-child(6) { animation-delay: 0.6s; }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #193948, #4FADC0, #D6BFBF);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 32px rgba(25, 57, 72, 0.3);
            border-color: #193948;
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-card.clickable {
            cursor: pointer;
        }

        .stat-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            box-shadow: 0 4px 12px rgba(25, 57, 72, 0.3);
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-card-icon {
            transform: rotate(5deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(25, 57, 72, 0.4);
        }

        .stat-card-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #193948;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
        }

        .stat-card-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: #193948;
            margin: 0.5rem 0;
            line-height: 1;
            text-shadow: 1px 1px 2px rgba(25, 57, 72, 0.1);
        }

        .stat-card-description {
            font-size: 0.8rem;
            color: #193948;
            opacity: 0.7;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        .stat-card-footer {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(25, 57, 72, 0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #193948;
            opacity: 0.6;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.875rem;
            margin-top: 1.5rem;
        }

        .action-button {
            background: linear-gradient(135deg, #D6BFBF 0%, #c4a8a8 100%);
            color: #193948;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(214, 191, 191, 0.3);
            border: 2px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .action-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(25, 57, 72, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .action-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .action-button:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(214, 191, 191, 0.4);
            border-color: #193948;
            color: #193948;
        }

        .action-button:active {
            transform: translateY(-2px);
        }

        .action-button-icon {
            font-size: 1.25rem;
            position: relative;
            z-index: 1;
        }

        .action-button-text {
            position: relative;
            z-index: 1;
        }

        .featured-card {
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 8px 24px rgba(25, 57, 72, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out, glow 3s infinite;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .featured-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(79, 173, 192, 0.15) 0%, transparent 70%);
            animation: pulse 4s infinite;
        }

        .featured-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 12px 36px rgba(25, 57, 72, 0.5);
        }

        .featured-card-content {
            position: relative;
            z-index: 1;
        }

        .featured-card-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
        }

        .featured-card-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #F3EBDD;
            margin-bottom: 1rem;
        }

        .featured-card-description {
            font-size: 1.1rem;
            color: #D6BFBF;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .featured-card-button {
            display: inline-block;
            background: linear-gradient(135deg, #D6BFBF 0%, #c4a8a8 100%);
            color: #193948;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(214, 191, 191, 0.3);
        }

        .featured-card-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(214, 191, 191, 0.4);
            background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%);
        }

        .wallet-card {
            background: linear-gradient(135deg, #4FADC0 0%, #3a8fa0 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 8px 24px rgba(79, 173, 192, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.7s ease-out;
            text-decoration: none;
            display: block;
        }

        .wallet-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(243, 235, 221, 0.1) 0%, transparent 70%);
            animation: shimmer 4s infinite;
        }

        .wallet-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 36px rgba(79, 173, 192, 0.4);
        }

        .wallet-card-content {
            position: relative;
            z-index: 1;
        }

        .wallet-card-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        .wallet-card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #F3EBDD;
            margin-bottom: 0.75rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .wallet-card-amount {
            font-size: 2.5rem;
            font-weight: 800;
            color: #F3EBDD;
            margin: 0.5rem 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .wallet-card-label {
            font-size: 0.875rem;
            color: #D6BFBF;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .wallet-card-button {
            display: inline-block;
            background: linear-gradient(135deg, #F3EBDD 0%, #e8ddd0 100%);
            color: #193948;
            padding: 0.625rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(25, 57, 72, 0.2);
        }

        .wallet-card-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(25, 57, 72, 0.3);
            background: linear-gradient(135deg, #ffffff 0%, #F3EBDD 100%);
        }

        .complaints-card {
            background: linear-gradient(135deg, #F3EBDD 0%, #e8ddd0 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 8px 24px rgba(25, 57, 72, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.7s ease-out;
            text-decoration: none;
            display: block;
            height: 100%;
        }

        .complaints-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(25, 57, 72, 0.05) 0%, transparent 70%);
            animation: shimmer 4s infinite;
        }

        .complaints-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 36px rgba(25, 57, 72, 0.3);
        }

        .complaints-card-content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .complaints-card-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        .complaints-card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 0.75rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .complaints-card-amount {
            font-size: 2.5rem;
            font-weight: 800;
            color: #193948;
            margin: 0.5rem 0;
            text-shadow: 2px 2px 4px rgba(25, 57, 72, 0.1);
        }

        .complaints-card-label {
            font-size: 0.875rem;
            color: #193948;
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .complaints-card-button {
            display: inline-block;
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            color: #F3EBDD;
            padding: 0.625rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(25, 57, 72, 0.2);
            margin-top: auto;
        }

        .complaints-card-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(25, 57, 72, 0.3);
            background: linear-gradient(135deg, #2a4a5a 0%, #193948 100%);
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1.25rem;
            }

            .dashboard-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1.5rem;
            }

            .dashboard-title {
                font-size: 1.75rem;
            }

            .dashboard-subtitle {
                font-size: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
            }

            .stat-card {
                padding: 1.5rem;
            }

            .stat-card-value {
                font-size: 2.5rem;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .featured-card {
                padding: 2rem;
            }

            .featured-card-icon {
                font-size: 4rem;
            }

            .wallet-card {
                padding: 2rem;
            }

            .wallet-card-amount {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card-value {
                font-size: 2rem;
            }
        }
    </style>
    
    <div style="padding: 1rem; max-width: 1400px; margin: 0 auto;">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="dashboard-header-content">
                <div class="dashboard-header-left">
                    <h1 class="dashboard-title">Artist Dashboard</h1>
                    <p class="dashboard-subtitle">Manage Your Artworks & Creative Portfolio</p>
                </div>
                @if($agency)
                <div class="dashboard-header-right">
                    <div class="dashboard-info">
                        <div class="dashboard-info-item">
                            <span class="dashboard-info-label">Agency:</span>
                            <span class="dashboard-info-value">{{ $agency->agency_name }}</span>
                        </div>
                        <div class="dashboard-info-item">
                            <span class="dashboard-info-label">Wilaya:</span>
                            <span class="dashboard-info-value">{{ $agency->wilaya }}</span>
                        </div>
                        <div class="dashboard-info-item">
                            <span class="dashboard-info-label">User:</span>
                            <span class="dashboard-info-value">{{ $user->name }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div style="margin-bottom: 1.5rem;">
            <div class="action-buttons">
                <a href="{{ route('artist.create-artwork') }}" class="action-button">
                    <span class="action-button-icon">📤</span>
                    <span class="action-button-text">Upload New Artwork</span>
                </a>
                <a href="{{ route('artist.artworks') }}" class="action-button">
                    <span class="action-button-icon">🎨</span>
                    <span class="action-button-text">All My Artworks</span>
                </a>
                <a href="{{ route('artist.artworks.live') }}" class="action-button">
                    <span class="action-button-icon">✨</span>
                    <span class="action-button-text">Live Artworks</span>
                </a>
                <a href="{{ route('artist.complaints.index') }}" class="action-button">
                    <span class="action-button-icon">📝</span>
                    <span class="action-button-text">Complaints</span>
                </a>
            </div>
        </div>

        <!-- Artworks Management Section -->
        <div class="stats-grid">
            <a href="{{ route('artist.artworks.live') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">✨</div>
                <div class="stat-card-title">Live Artworks</div>
                <div class="stat-card-value">{{ $liveArtworksCount }}</div>
                <div class="stat-card-description">Active and enabled</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Live Artworks</span>
                </div>
            </a>

            <a href="{{ route('artist.artworks') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">📊</div>
                <div class="stat-card-title">Total Artworks</div>
                <div class="stat-card-value">{{ $artworksCount }}</div>
                <div class="stat-card-description">All artworks uploaded</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View All Artworks</span>
                </div>
            </a>

            <a href="{{ route('artist.artworks.pending') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">⏳</div>
                <div class="stat-card-title">Pending Approval</div>
                <div class="stat-card-value">{{ $pendingArtworksCount }}</div>
                <div class="stat-card-description">Awaiting review</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Pending</span>
                </div>
            </a>

            <a href="{{ route('artist.artworks.pending-payment') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">💳</div>
                <div class="stat-card-title">Pending Payment</div>
                <div class="stat-card-value">{{ $pendingPaymentCount }}</div>
                <div class="stat-card-description">Awaiting payment</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View & Pay</span>
                </div>
            </a>

            <a href="{{ route('artist.artworks.rejected') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">❌</div>
                <div class="stat-card-title">Rejected</div>
                <div class="stat-card-value">{{ $rejectedArtworksCount }}</div>
                <div class="stat-card-description">Rejected artworks</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Rejected</span>
                </div>
            </a>
        </div>

        <!-- Wallet & Account Section -->
        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <a href="{{ route('artist.wallet') }}" class="wallet-card">
                <div class="wallet-card-content">
                    <div class="wallet-card-icon">💰</div>
                    <div class="wallet-card-title">My Wallet</div>
                    <div class="wallet-card-amount">{{ number_format($balance, 2) }} DZD</div>
                    <div class="wallet-card-label">Current Balance</div>
                    <div class="wallet-card-button">Manage Wallet</div>
                </div>
            </a>

            <a href="{{ route('artist.complaints.index') }}" class="complaints-card">
                <div class="complaints-card-content">
                    <div class="complaints-card-icon">📝</div>
                    <div class="complaints-card-title">All Complaints</div>
                    <div class="complaints-card-amount">{{ $complaintsCount }}</div>
                    <div class="complaints-card-label">Filed complaints</div>
                    <div class="complaints-card-button">View Complaints</div>
                </div>
            </a>
        </div>

        <!-- Support & Legal Section -->
        <div class="stats-grid">
            <a href="{{ route('help') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">❓</div>
                <div class="stat-card-title">Help</div>
                <div class="stat-card-value" style="font-size: 1.75rem;">💡</div>
                <div class="stat-card-description">If you need an explanation of how the site works</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>Get Help</span>
                </div>
            </a>

            <a href="{{ route('artist.law') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">⚖️</div>
                <div class="stat-card-title">Legal Reference</div>
                <div class="stat-card-value" style="font-size: 1.75rem;">📚</div>
                <div class="stat-card-description">Learn about your rights</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Legal Info</span>
                </div>
            </a>
        </div>
    </div>
</x-allthepages-layout>

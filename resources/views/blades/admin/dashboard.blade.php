<x-allthepages-layout pageTitle="Admin Dashboard">
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
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #F3EBDD 0%, #e8ddd0 100%);
            border-radius: 1.25rem;
            padding: 2rem;
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
        .stat-card:nth-child(7) { animation-delay: 0.7s; }
        .stat-card:nth-child(8) { animation-delay: 0.8s; }

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
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            box-shadow: 0 4px 12px rgba(25, 57, 72, 0.3);
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-card-icon {
            transform: rotate(5deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(25, 57, 72, 0.4);
        }

        .stat-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #193948;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
        }

        .stat-card-value {
            font-size: 3rem;
            font-weight: 800;
            color: #193948;
            margin: 1rem 0;
            line-height: 1;
            text-shadow: 1px 1px 2px rgba(25, 57, 72, 0.1);
        }

        .stat-card-description {
            font-size: 0.9rem;
            color: #193948;
            opacity: 0.7;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .stat-card-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(25, 57, 72, 0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #193948;
            opacity: 0.6;
        }

        .large-stat-card {
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 8px 24px rgba(25, 57, 72, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }

        .large-stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(79, 173, 192, 0.1) 0%, transparent 70%);
            animation: pulse 4s infinite;
        }

        .large-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 36px rgba(25, 57, 72, 0.5);
        }

        .large-stat-card-content {
            position: relative;
            z-index: 1;
        }

        .large-stat-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #D6BFBF;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .large-stat-card-value {
            font-size: 3.5rem;
            font-weight: 800;
            color: #F3EBDD;
            margin: 1rem 0;
            line-height: 1;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .large-stat-card-description {
            font-size: 1rem;
            color: #D6BFBF;
            margin-top: 0.75rem;
            opacity: 0.9;
        }

        .large-stat-card-button {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(214, 191, 191, 0.2);
        }

        .large-stat-card-button a {
            display: inline-block;
            background: linear-gradient(135deg, #D6BFBF 0%, #c4a8a8 100%);
            color: #193948;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(214, 191, 191, 0.3);
        }

        .large-stat-card-button a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(214, 191, 191, 0.4);
            background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%);
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

            .large-stat-card {
                padding: 2rem;
            }

            .large-stat-card-value {
                font-size: 2.5rem;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card-value {
                font-size: 2rem;
            }

            .large-stat-card-value {
                font-size: 2rem;
            }
        }
    </style>
    
    <div style="padding: 1rem; max-width: 1400px; margin: 0 auto;">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="dashboard-header-content">
                <div class="dashboard-header-left">
                    <h1 class="dashboard-title">Admin Dashboard</h1>
                    <p class="dashboard-subtitle">Agency Management & Control Center</p>
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
                            <span class="dashboard-info-value">{{ $admin->name }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div style="margin-bottom: 1.5rem;">
            <div class="action-buttons">
                <a href="{{ route('admin.manage-users') }}" class="action-button">
                    <span class="action-button-icon">👥</span>
                    <span class="action-button-text">Manage Users</span>
                </a>
                <a href="{{ route('admin.manage-gestionnaires') }}" class="action-button">
                    <span class="action-button-icon">👔</span>
                    <span class="action-button-text">Manage Gestionnaires</span>
                </a>
                <a href="{{ route('admin.manage-agents') }}" class="action-button">
                    <span class="action-button-icon">🕵️</span>
                    <span class="action-button-text">Manage Agents</span>
                </a>
                <a href="{{ route('admin.manage-missions') }}" class="action-button">
                    <span class="action-button-icon">🎯</span>
                    <span class="action-button-text">Manage Missions</span>
                </a>
                <a href="{{ route('admin.manage-pvs') }}" class="action-button">
                    <span class="action-button-icon">📋</span>
                    <span class="action-button-text">View All PVs</span>
                </a>
            </div>
        </div>

        <!-- Main Statistics Grid -->
        <div class="stats-grid">
            <a href="{{ route('admin.manage-users') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">👤</div>
                <div class="stat-card-title">Pending Artists</div>
                <div class="stat-card-value">{{ $pendingArtistsCount }}</div>
                <div class="stat-card-description">Awaiting approval</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>Review & Approve</span>
                </div>
            </a>

            <a href="{{ route('admin.reports-and-complaints.index', ['type' => 'report', 'status' => 'PENDING']) }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">📊</div>
                <div class="stat-card-title">Pending Reports</div>
                <div class="stat-card-value">{{ $pendingReportsCount }}</div>
                <div class="stat-card-description">Requires review</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>Review Reports</span>
                </div>
            </a>

            <a href="{{ route('admin.reports-and-complaints.index', ['type' => 'complaint', 'status' => 'PENDING']) }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">📝</div>
                <div class="stat-card-title">Pending Complaints</div>
                <div class="stat-card-value">{{ $complaintsCount }}</div>
                <div class="stat-card-description">Requires review</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>Review Complaints</span>
                </div>
            </a>

            <a href="{{ route('admin.manage-gestionnaires') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">👔</div>
                <div class="stat-card-title">Gestionnaires</div>
                <div class="stat-card-value">{{ $gestionnairesCount }}</div>
                <div class="stat-card-description">Team members</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>Manage Team</span>
                </div>
            </a>

            <a href="{{ route('admin.manage-agents') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">🕵️</div>
                <div class="stat-card-title">Agents</div>
                <div class="stat-card-value">{{ $agentsCount }}</div>
                <div class="stat-card-description">Active agents</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Agents</span>
                </div>
            </a>

            <a href="{{ route('admin.manage-missions', ['status' => 'ASSIGNED']) }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">🎯</div>
                <div class="stat-card-title">Missions Assigned</div>
                <div class="stat-card-value">{{ $missionAssigned }}</div>
                <div class="stat-card-description">{{ $missionInProgress }} in progress</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Assigned Missions</span>
                </div>
            </a>

            <a href="{{ route('admin.manage-pvs') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">📋</div>
                <div class="stat-card-title">All PVs</div>
                <div class="stat-card-value">{{ $allPvsCount }}</div>
                <div class="stat-card-description">Total PVs</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View All PVs</span>
                </div>
            </a>

            <a href="{{ route('admin.financial-transactions') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">💳</div>
                <div class="stat-card-title">Transactions</div>
                <div class="stat-card-value">{{ $artistTransactionsCount }}</div>
                <div class="stat-card-description">Total transactions</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Transactions</span>
                </div>
            </a>
        </div>

        <!-- Large Statistics Cards -->
        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <div class="large-stat-card">
                <div class="large-stat-card-content">
                    <div class="large-stat-card-title">Agency Wallet Balance</div>
                    <div class="large-stat-card-value">{{ number_format($wallet->balance, 0) }}</div>
                    <div class="large-stat-card-description">DZD available</div>
                    <div class="large-stat-card-button">
                        <a href="{{ route('admin.financial-transactions') }}">View Transactions</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</x-allthepages-layout>

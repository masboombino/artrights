<x-allthepages-layout pageTitle="Agent Dashboard">
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
            padding: 1rem 1.1rem;
            box-shadow: 0 4px 16px rgba(25, 57, 72, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
            border: 2px solid transparent;
            text-align: left;
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
            width: 44px;
            height: 44px;
            border-radius: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.45rem;
            margin: 0 0.55rem 0.35rem 0;
            vertical-align: middle;
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            box-shadow: 0 4px 12px rgba(25, 57, 72, 0.3);
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-card-icon {
            transform: rotate(5deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(25, 57, 72, 0.4);
        }

        .stat-card-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #193948;
            margin-bottom: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            vertical-align: middle;
        }

        .stat-card-value {
            font-size: 2.15rem;
            font-weight: 800;
            color: #193948;
            margin: 0.3rem 0;
            line-height: 1;
            text-shadow: 1px 1px 2px rgba(25, 57, 72, 0.1);
            text-align: right;
            width: 100%;
        }

        .stat-card-description {
            display: none;
        }

        .stat-card-footer {
            margin-top: 0.35rem;
            padding-top: 0.45rem;
            border-top: 1px solid rgba(25, 57, 72, 0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.78rem;
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
                padding: 0.95rem;
            }

            .stat-card-value {
                font-size: 1.95rem;
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
        }
    </style>
    
    <div style="padding: 1rem; max-width: 1400px; margin: 0 auto;">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="dashboard-header-content">
                <div class="dashboard-header-left">
                    <h1 class="dashboard-title">Agent Dashboard</h1>
                    <p class="dashboard-subtitle">Field Operations & Inspection Management</p>
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
                <a href="{{ route('agent.pvs.create') }}" class="action-button">
                    <span class="action-button-icon">➕</span>
                    <span class="action-button-text">Create New PV</span>
                </a>
                <a href="{{ route('agent.pvs.index') }}" class="action-button">
                    <span class="action-button-icon">📋</span>
                    <span class="action-button-text">View All PVs</span>
                </a>
                <a href="{{ route('agent.missions.index') }}" class="action-button">
                    <span class="action-button-icon">🎯</span>
                    <span class="action-button-text">View All Missions</span>
                </a>
                <a href="{{ route('agent.complaints.index') }}" class="action-button">
                    <span class="action-button-icon">📝</span>
                    <span class="action-button-text">Complaints & Reports</span>
                </a>
            </div>
        </div>

        <!-- Missions Section -->
        <div class="stats-grid">
            <a href="{{ route('agent.missions.index', ['status' => 'ASSIGNED']) }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">📌</div>
                <div class="stat-card-title">Missions Assigned</div>
                <div class="stat-card-value">{{ $missionStats['assigned'] ?? 0 }}</div>
                <div class="stat-card-description">Total missions</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Missions</span>
                </div>
            </a>

            <a href="{{ route('agent.missions.index', ['status' => 'IN_PROGRESS']) }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">🔄</div>
                <div class="stat-card-title">In Progress</div>
                <div class="stat-card-value">{{ $missionStats['in_progress'] ?? 0 }}</div>
                <div class="stat-card-description">Active missions</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>Track Progress</span>
                </div>
            </a>

            <a href="{{ route('agent.missions.index', ['status' => 'DONE']) }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">✅</div>
                <div class="stat-card-title">Completed Missions</div>
                <div class="stat-card-value">{{ $missionStats['done'] ?? 0 }}</div>
                <div class="stat-card-description">Successfully finished</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View History</span>
                </div>
            </a>
        </div>

        <!-- PVs Section -->
        <div class="stats-grid">
            <a href="{{ route('agent.pvs.index') }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">📊</div>
                <div class="stat-card-title">Total PVs</div>
                <div class="stat-card-value">{{ $stats['total'] }}</div>
                <div class="stat-card-description">All reports created</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View All PVs</span>
                </div>
            </a>

            <a href="{{ route('agent.pvs.index', ['status' => 'OPEN']) }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">📄</div>
                <div class="stat-card-title">Open PVs</div>
                <div class="stat-card-value">{{ $stats['open'] }}</div>
                <div class="stat-card-description">Currently active</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Open</span>
                </div>
            </a>

            <a href="{{ route('agent.pvs.index', ['status' => 'PENDING']) }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">⏳</div>
                <div class="stat-card-title">Pending PVs</div>
                <div class="stat-card-value">{{ $stats['pending'] }}</div>
                <div class="stat-card-description">Awaiting review</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Pending</span>
                </div>
            </a>

            <a href="{{ route('agent.pvs.index', ['status' => 'CLOSED']) }}" class="stat-card clickable" style="text-decoration: none;">
                <div class="stat-card-icon">✔️</div>
                <div class="stat-card-title">Closed PVs</div>
                <div class="stat-card-value">{{ $stats['closed'] }}</div>
                <div class="stat-card-description">Completed reports</div>
                <div class="stat-card-footer">
                    <span>→</span>
                    <span>View Closed</span>
                </div>
            </a>
        </div>
    </div>

    <script>
        function showAgentDashboardToast(message, type = 'info') {
            if (!message) return;

            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 14px 20px;
                background-color: ${type === 'error' ? '#E76268' : type === 'success' ? '#4FADC0' : '#193948'};
                color: #193948;
                border: 2px solid #193948;
                border-radius: 10px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                z-index: 10000;
                font-weight: 700;
                max-width: min(420px, 92vw);
                line-height: 1.4;
                animation: agentToastSlideIn 0.3s ease-out;
            `;
            toast.textContent = message;

            if (!document.querySelector('style[data-agent-dashboard-toast-style]')) {
                const style = document.createElement('style');
                style.setAttribute('data-agent-dashboard-toast-style', '');
                style.textContent = `
                    @keyframes agentToastSlideIn {
                        from { transform: translateX(420px); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes agentToastSlideOut {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(420px); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'agentToastSlideOut 0.25s ease-in forwards';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 260);
            }, 4500);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = @json(session('success'));
            const errorMessage = @json(session('error'));

            if (successMessage) {
                showAgentDashboardToast(successMessage, 'success');
            }

            if (errorMessage) {
                showAgentDashboardToast(errorMessage, 'error');
            }
        });
    </script>
</x-allthepages-layout>

<x-allthepages-layout pageTitle="لوحة الشكاوى - السوبر أدمن">
    <style>
        .complaints-dashboard {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #D1FAE5 0%, #6EE7B7 100%);
            border: 2px solid #10b981;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            color: #193948;
            font-weight: 600;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .header-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #193948;
            margin: 0 0 0.5rem 0;
        }
        
        .header-title p {
            font-size: 1.1rem;
            color: #193948;
            opacity: 0.8;
            margin: 0;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-action {
            background: linear-gradient(135deg, #E76268 0%, #d4555a 100%);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            border: 2px solid #193948;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 98, 104, 0.3);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%);
            border: 2px solid #193948;
            border-radius: 1rem;
            padding: 1.75rem;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(25, 57, 72, 0.2);
        }
        
        .stat-value {
            font-size: 2.75rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #193948;
            opacity: 0.8;
        }
        
        .tabs-section {
            margin-bottom: 2rem;
        }
        
        .tabs {
            display: flex;
            gap: 0.5rem;
            border-bottom: 3px solid #D6BFBF;
            flex-wrap: wrap;
        }
        
        .tab-link {
            padding: 1rem 2rem;
            font-weight: 600;
            color: #193948;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            top: 3px;
        }
        
        .tab-link.active {
            border-bottom-color: #193948;
            color: #193948;
        }
        
        .tab-link:not(.active) {
            opacity: 0.7;
        }
        
        .tab-link:not(.active):hover {
            opacity: 1;
            border-bottom-color: #4FADC0;
        }
        
        .filters-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-form {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-select {
            padding: 0.75rem 1rem;
            border: 2px solid #193948;
            border-radius: 0.5rem;
            color: #193948;
            background-color: white;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .btn-filter {
            background: #193948;
            color: #4FADC0;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            border: 2px solid #193948;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-filter:hover {
            background: #2a4a5a;
            transform: translateY(-2px);
        }
        
        .btn-clear {
            background: #D6BFBF;
            color: #193948;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-clear:hover {
            background: #c4a8a8;
        }
        
        .table-container {
            background: #F3EBDD;
            border: 2px solid #193948;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(25, 57, 72, 0.1);
        }
        
        .table-wrapper {
            overflow-x: auto;
            width: 100%;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }
        
        .data-table thead {
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
        }
        
        .data-table th {
            padding: 1.25rem 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4FADC0;
        }
        
        .data-table tbody tr {
            border-top: 1px solid rgba(25, 57, 72, 0.1);
            transition: background 0.3s ease;
        }
        
        .data-table tbody tr:hover {
            background: rgba(214, 191, 191, 0.3);
        }
        
        .data-table td {
            padding: 1.25rem 1.5rem;
            text-align: center;
            color: #193948;
            font-size: 0.95rem;
        }
        
        .type-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #E76268 0%, #d4555a 100%);
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: white;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .status-resolved {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .status-progress {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }
        
        .btn-view {
            background: #193948;
            color: #4FADC0;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn-view:hover {
            background: #2a4a5a;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(25, 57, 72, 0.3);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }
        
        .empty-text {
            font-size: 1.25rem;
            color: #193948;
            font-weight: 600;
        }
        
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .complaints-dashboard {
                padding: 1rem 0.5rem;
            }
            
            .header-title h2 {
                font-size: 1.75rem;
            }
            
            .stat-value {
                font-size: 2rem;
            }
        }
    </style>

    <div class="complaints-dashboard">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-title">
                <h2>لوحة الشكاوى</h2>
                <p>إدارة جميع الشكاوى في النظام</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('superadmin.complaints.create', ['type' => 'complaint']) }}" class="btn-action">
                    <span>⚠️</span>
                    <span>تقديم شكوى</span>
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_complaints'] ?? 0 }}</div>
                <div class="stat-label">إجمالي الشكاوى</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color: #E76268;">{{ $stats['pending_complaints'] ?? 0 }}</div>
                <div class="stat-label">الشكاوى المعلقة</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color: #6366f1;">{{ $stats['admin_complaints'] ?? 0 }}</div>
                <div class="stat-label">شكاوى الإدارة</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs-section">
            <div class="tabs">
                <a href="{{ route('superadmin.complaints') }}" 
                   class="tab-link {{ ($type ?? 'all') === 'all' ? 'active' : '' }}">
                    الكل
                </a>
                <a href="{{ route('superadmin.complaints', ['type' => 'complaint']) }}" 
                   class="tab-link {{ ($type ?? 'all') === 'complaint' ? 'active' : '' }}">
                    ⚠️ الشكاوى
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('superadmin.complaints') }}" class="filter-form">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <select name="status" class="filter-select">
                    <option value="">جميع الحالات</option>
                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>معلق</option>
                    <option value="IN_PROGRESS" {{ request('status') === 'IN_PROGRESS' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="RESOLVED" {{ request('status') === 'RESOLVED' ? 'selected' : '' }}>تم الحل</option>
                </select>
                <button type="submit" class="btn-filter">تصفية</button>
                @if(request('status') || request('type'))
                    <a href="{{ route('superadmin.complaints') }}" class="btn-clear">مسح</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="table-container">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>النوع</th>
                            <th>من</th>
                            <th>الموضوع</th>
                            <th>الرسالة</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>
                                    <span class="type-badge">⚠️ شكوى</span>
                                </td>
                                <td style="font-weight: 600;">
                                    {{ $item->sender?->name ?? ucfirst($item->sender_role ?? 'غير معروف') }}
                                </td>
                                <td style="font-weight: 600;">{{ $item->subject }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($item->message, 50) }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower(str_replace('_', '-', $item->status)) }}">
                                        {{ str_replace('_', ' ', $item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('superadmin.view-admin-complaint', $item->id) }}" class="btn-view">
                                        فتح
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <div class="empty-icon">📭</div>
                                    <div class="empty-text">لا توجد عناصر</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="pagination-wrapper">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>

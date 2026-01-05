<x-allthepages-layout pageTitle="Messages Center - Complaints & Reports">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success" style="margin-bottom: 1.5rem;">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 2rem; padding: 1rem; background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%); border-radius: 1rem; border: 2px solid #193948;">
            <div>
                <h1 style="color: #D6BFBF; font-size: 2rem; font-weight: 700; margin: 0;">💬 Messages Center</h1>
                <p style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem; opacity: 0.8;">Unified system for Complaints & Reports</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('admin.messages.create', ['type' => 'complaint']) }}" style="padding: 12px 24px; background-color: #E76268; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    ⚠️ New Complaint
                </a>
                <a href="{{ route('admin.messages.create', ['type' => 'report']) }}" style="padding: 12px 24px; background-color: #10b981; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    📊 New Report
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 2rem;">
            <div class="page-container" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%); border: 2px solid #193948;">
                <div style="font-size: 2.5rem; font-weight: 700; color: #193948;">{{ $stats['total_complaints'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem;">Total Complaints</div>
            </div>
            <div class="page-container" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #FEE2E2 0%, #FCA5A5 100%); border: 2px solid #E76268;">
                <div style="font-size: 2.5rem; font-weight: 700; color: #E76268;">{{ $stats['pending_complaints'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem;">Pending Complaints</div>
            </div>
            <div class="page-container" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #D1FAE5 0%, #6EE7B7 100%); border: 2px solid #10b981;">
                <div style="font-size: 2.5rem; font-weight: 700; color: #10b981;">{{ $stats['total_reports'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem;">Total Reports</div>
            </div>
            <div class="page-container" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border: 2px solid #f59e0b;">
                <div style="font-size: 2.5rem; font-weight: 700; color: #f59e0b;">{{ $stats['pending_reports'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem;">Pending Reports</div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div style="display: flex; gap: 5px; border-bottom: 3px solid #D6BFBF; margin-bottom: 1.5rem; flex-wrap: wrap; background-color: #F3EBDD; padding: 0.5rem; border-radius: 0.5rem 0.5rem 0 0;">
            <a href="{{ route('admin.messages.index') }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid {{ ($type ?? 'all') === 'all' ? '#193948' : 'transparent' }}; transition: all 0.3s;">
                📋 All Messages
            </a>
            <a href="{{ route('admin.messages.index', ['type' => 'complaint']) }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid {{ ($type ?? 'all') === 'complaint' ? '#E76268' : 'transparent' }}; transition: all 0.3s;">
                ⚠️ Complaints
            </a>
            <a href="{{ route('admin.messages.index', ['type' => 'report']) }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid {{ ($type ?? 'all') === 'report' ? '#10b981' : 'transparent' }}; transition: all 0.3s;">
                📊 Reports
            </a>
            <a href="{{ route('admin.messages.inbox') }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid transparent; transition: all 0.3s;">
                📥 Inbox
            </a>
            <a href="{{ route('admin.messages.sent') }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid transparent; transition: all 0.3s;">
                📤 Sent
            </a>
        </div>

        <!-- Filters -->
        <div style="display: flex; gap: 10px; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: center; padding: 1rem; background-color: #F3EBDD; border-radius: 0.5rem; border: 2px solid #D6BFBF;">
            <form method="GET" action="{{ route('admin.messages.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; flex: 1;">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <label style="color: #193948; font-weight: 600; font-size: 0.9rem;">Filter by Status:</label>
                <select name="status" style="padding: 8px 16px; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; background-color: white; font-size: 0.9rem;">
                    <option value="">All Status</option>
                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="IN_PROGRESS" {{ request('status') === 'IN_PROGRESS' ? 'selected' : '' }}>🔄 In Progress</option>
                    <option value="RESOLVED" {{ request('status') === 'RESOLVED' ? 'selected' : '' }}>✅ Resolved</option>
                </select>
                <button type="submit" style="padding: 8px 20px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; border: none; font-weight: 600; cursor: pointer; font-size: 0.9rem;">
                    🔍 Apply Filter
                </button>
                @if(request('status') || request('type'))
                    <a href="{{ route('admin.messages.index') }}" style="padding: 8px 20px; background-color: #D6BFBF; color: #193948; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                        🗑️ Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Messages List -->
        <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD; border: 2px solid #193948;">
            @if($items->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="w-full" style="border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #193948;">
                                <th style="color: #4FADC0; padding: 1rem; text-align: left; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Type</th>
                                <th style="color: #4FADC0; padding: 1rem; text-align: left; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">From</th>
                                <th style="color: #4FADC0; padding: 1rem; text-align: left; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Subject</th>
                                <th style="color: #4FADC0; padding: 1rem; text-align: left; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Preview</th>
                                <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Status</th>
                                <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Date</th>
                                <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr style="border-top: 1px solid rgba(25, 57, 72, 0.1); background-color: {{ $loop->even ? '#F9F9F9' : 'white' }};">
                                    <td style="padding: 1rem;">
                                        <span style="padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background-color: {{ $item->type === 'COMPLAINT' ? '#E76268' : '#10b981' }}; color: white; display: inline-block;">
                                            {{ $item->type === 'COMPLAINT' ? '⚠️ Complaint' : '📊 Report' }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; color: #193948; font-weight: 600;">
                                        {{ $item->sender?->name ?? ucfirst($item->sender_role ?? 'Unknown') }}
                                    </td>
                                    <td style="padding: 1rem; color: #193948; font-weight: 600;">{{ $item->subject }}</td>
                                    <td style="padding: 1rem; color: #193948; font-size: 0.85rem;">
                                        {{ \Illuminate\Support\Str::limit($item->message, 60) }}
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <span style="padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background-color: 
                                            @if($item->status === 'PENDING') #f59e0b 
                                            @elseif($item->status === 'RESOLVED') #10b981 
                                            @else #6366f1 @endif; color: white; display: inline-block;">
                                            {{ str_replace('_', ' ', $item->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; text-align: center; color: #193948; font-size: 0.85rem;">
                                        {{ $item->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.messages.show', $item->id) }}" style="padding: 6px 14px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                                                👁️ View
                                            </a>
                                            @if($item->status === 'PENDING')
                                                <form action="{{ route('admin.messages.resolve', $item->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" style="padding: 6px 14px; background-color: #10b981; color: white; border-radius: 0.5rem; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                                        ✅ Resolve
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="padding: 4rem; text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
                    <h3 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">No Messages Found</h3>
                    <p style="color: #193948; margin-bottom: 1.5rem; opacity: 0.8;">No messages match your current filters.</p>
                    <a href="{{ route('admin.messages.index') }}" style="padding: 12px 24px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">
                        View All Messages
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>







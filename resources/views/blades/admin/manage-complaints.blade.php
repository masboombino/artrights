<x-allthepages-layout pageTitle="Complaints & Reports Dashboard">
    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 rounded mb-4" style="background-color: #D1FAE5; color: #193948; border: 2px solid #10b981;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 1.5rem;">
            <div>
                <h2 style="color: #D6BFBF; font-size: 2rem; font-weight: 700; margin: 0;">Complaints & Reports Dashboard</h2>
                <p style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem; opacity: 0.8;">Manage all complaints and reports for your agency</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('admin.complaints.create', ['type' => 'complaint']) }}" class="primary-button" style="background-color: #E76268;">
                    ⚠️ Submit Complaint
                </a>
                <a href="{{ route('admin.complaints.create', ['type' => 'report']) }}" class="primary-button" style="background-color: #10b981;">
                    📊 Submit Report
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 2rem;">
            <div class="page-container" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%);">
                <div style="font-size: 2.5rem; font-weight: 700; color: #193948;">{{ $stats['total_complaints'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem;">Total Complaints</div>
            </div>
            <div class="page-container" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #FEE2E2 0%, #FCA5A5 100%);">
                <div style="font-size: 2.5rem; font-weight: 700; color: #E76268;">{{ $stats['pending_complaints'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem;">Pending Complaints</div>
            </div>
            <div class="page-container" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #D1FAE5 0%, #6EE7B7 100%);">
                <div style="font-size: 2.5rem; font-weight: 700; color: #10b981;">{{ $stats['total_reports'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem;">Total Reports</div>
            </div>
            <div class="page-container" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);">
                <div style="font-size: 2.5rem; font-weight: 700; color: #f59e0b;">{{ $stats['pending_reports'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem;">Pending Reports</div>
            </div>
        </div>

        <!-- Tabs -->
        <div style="display: flex; gap: 10px; border-bottom: 3px solid #D6BFBF; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <a href="{{ route('admin.manage-complaints') }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; border-bottom: 3px solid #193948; text-decoration: none;">
                All Items
            </a>
            <a href="{{ route('admin.manage-complaints', ['type' => 'complaint']) }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; opacity: {{ ($type ?? 'all') === 'complaint' ? '1' : '0.7' }}; border-bottom: {{ ($type ?? 'all') === 'complaint' ? '3px solid #E76268' : 'none' }};">
                ⚠️ Complaints
            </a>
            <a href="{{ route('admin.manage-complaints', ['type' => 'report']) }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; opacity: {{ ($type ?? 'all') === 'report' ? '1' : '0.7' }}; border-bottom: {{ ($type ?? 'all') === 'report' ? '3px solid #10b981' : 'none' }};">
                📊 Reports
            </a>
            <a href="{{ route('admin.complaints.inbox') }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; opacity: 0.7;">
                📥 Inbox
            </a>
            <a href="{{ route('admin.complaints.sent') }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; opacity: 0.7;">
                📤 Sent
            </a>
        </div>

        <!-- Filters -->
        <div style="display: flex; gap: 10px; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: center;">
            <form method="GET" action="{{ route('admin.messages.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <select name="status" style="padding: 8px 12px; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; background-color: white;">
                    <option value="">All Status</option>
                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="IN_PROGRESS" {{ request('status') === 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                    <option value="RESOLVED" {{ request('status') === 'RESOLVED' ? 'selected' : '' }}>Resolved</option>
                </select>
                <button type="submit" style="padding: 8px 16px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; border: none; font-weight: 600; cursor: pointer;">
                    Filter
                </button>
                @if(request('status') || request('type'))
                    <a href="{{ route('admin.messages.index') }}" style="padding: 8px 16px; background-color: #D6BFBF; color: #193948; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Items Table -->
        <div class="rounded-lg shadow overflow-hidden" style="background-color: #F3EBDD; border: 2px solid #193948;">
            <div style="overflow-x: auto;">
                <table class="w-full" style="border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #193948;">
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Type</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">From</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Subject</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Message</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Status</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Date</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr style="border-top: 1px solid rgba(0,0,0,0.1);">
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">
                                    <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background-color: {{ $item->type === 'COMPLAINT' ? '#E76268' : '#10b981' }}; color: white;">
                                        {{ $item->type === 'COMPLAINT' ? '⚠️ Complaint' : '📊 Report' }}
                                    </span>
                                </td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">
                                    {{ $item->sender?->name ?? ucfirst($item->sender_role ?? 'Unknown') }}
                                </td>
                                <td class="text-center text-sm" style="color: #193948; padding: 1rem 1.5rem; font-weight: 600;">{{ $item->subject }}</td>
                                <td class="text-center text-sm" style="color: #193948; padding: 1rem 1.5rem;">{{ \Illuminate\Support\Str::limit($item->message, 50) }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">
                                    <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background-color: 
                                        @if($item->status === 'PENDING') #f59e0b 
                                        @elseif($item->status === 'RESOLVED') #10b981 
                                        @else #6366f1 @endif; color: white;">
                                        {{ str_replace('_', ' ', $item->status) }}
                                    </span>
                                </td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">
                                    {{ $item->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="text-center text-sm whitespace-nowrap" style="padding: 1rem 1.5rem;">
                                    <div style="display: flex; gap: 5px; justify-content: center;">
                                        <a href="{{ route('admin.view-complaint', $item->id) }}" style="padding: 6px 12px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                                            Open
                                        </a>
                                        @if($item->status === 'PENDING')
                                            <form action="{{ route('admin.resolve-complaint', $item->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" style="padding: 6px 12px; background-color: #10b981; color: white; border-radius: 0.5rem; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                                    Resolve
                                                </button>
                                            </form>
                                        @endif
                                        @if($item->type === 'COMPLAINT')
                                            <form action="{{ route('admin.complaints.delete', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="padding: 6px 12px; background-color: #E76268; color: white; border-radius: 0.5rem; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-sm" style="color: #193948; padding: 2rem;">
                                    <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                    <p>No items found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 1.5rem;">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>

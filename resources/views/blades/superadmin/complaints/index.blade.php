<x-allthepages-layout pageTitle="Reports and Complaints">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="stat-card" style="margin-bottom: 10px; padding: 1rem; background-color: #D1FAE5; border: 2px solid #10b981;">
                <p style="color: #193948; margin: 0;">{{ session('success') }}</p>
            </div>
        @endif

        @php
            $totalItems = $stats['total_complaints'] ?? 0;
            $respondedCount = 0;
            foreach($items as $item) {
                if($item->admin_response || $item->gestionnaire_response || $item->super_admin_response) {
                    $respondedCount++;
                }
            }
        @endphp

        <!-- Statistics -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 10px;">
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <div class="stat-card" style="padding: 1.5rem; text-align: center; min-width: 120px;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #193948;">{{ $totalItems }}</div>
                    <div style="color: #193948; font-size: 0.85rem;">Total Complaints</div>
                </div>
                <div class="stat-card" style="padding: 1.5rem; text-align: center; min-width: 120px;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #10b981;">{{ $respondedCount }}</div>
                    <div style="color: #193948; font-size: 0.85rem;">Responded</div>
                </div>
                <div class="stat-card" style="padding: 1.5rem; text-align: center; min-width: 120px;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #f59e0b;">{{ $stats['pending_complaints'] ?? 0 }}</div>
                    <div style="color: #193948; font-size: 0.85rem;">Pending</div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div style="display: flex; gap: 5px; margin-bottom: 10px; flex-wrap: wrap; background-color: #F3EBDD; padding: 0.5rem; border: 2px solid #193948;">
            <a href="{{ route('superadmin.complaints.index') }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid {{ (request('type') ?? 'all') === 'all' ? '#193948' : 'transparent' }};">
                📋 All
            </a>
            <a href="{{ route('superadmin.complaints.index', ['type' => 'complaint']) }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid {{ request('type') === 'complaint' ? '#E76268' : 'transparent' }};">
                ⚠️ Complaints
            </a>
            <a href="{{ route('superadmin.reports.index', ['type' => 'report']) }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid {{ request('type') === 'report' ? '#10b981' : 'transparent' }};">
                📊 Reports
            </a>
        </div>

        <!-- Filters -->
        <div class="stat-card" style="padding: 1rem; margin-bottom: 10px;">
            <form method="GET" action="{{ route('superadmin.complaints.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <label style="color: #193948; font-weight: 600; font-size: 0.9rem;">Filter by Status:</label>
                <select name="status" style="padding: 8px 16px; border: 2px solid #193948; color: #193948; background-color: white; font-size: 0.9rem;">
                    <option value="">All Status</option>
                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="IN_PROGRESS" {{ request('status') === 'IN_PROGRESS' ? 'selected' : '' }}>🔄 In Progress</option>
                    <option value="RESOLVED" {{ request('status') === 'RESOLVED' ? 'selected' : '' }}>✅ Resolved</option>
                </select>
                <button type="submit" class="primary-button" style="padding: 8px 20px; font-size: 0.9rem;">
                    🔍 Apply Filter
                </button>
                @if(request('status') || request('type'))
                    <a href="{{ route('superadmin.complaints.index') }}" class="secondary-button" style="padding: 8px 20px; font-size: 0.9rem; text-decoration: none;">
                        🗑️ Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Messages List -->
        <div class="stat-card" style="padding: 0; margin-bottom: 10px; border: 2px solid #193948; border-radius: 0.5rem; overflow: hidden;">
            @if($items->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);">
                                <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Type</th>
                                <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">From</th>
                                <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Subject</th>
                                <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Status</th>
                                <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Date</th>
                                <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr style="border-bottom: 1px solid rgba(25, 57, 72, 0.1); background-color: {{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#F3EBDD'" onmouseout="this.style.backgroundColor='{{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}'">
                                    <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                        <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: {{ $item->type === 'COMPLAINT' ? '#E76268' : '#10b981' }}; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            {{ $item->type === 'COMPLAINT' ? '⚠️ Complaint' : '📊 Report' }}
                                        </span>
                                    </td>
                                    <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                        <div style="font-weight: 700;">{{ $item->sender?->name ?? ucfirst($item->sender_role ?? 'Unknown') }}</div>
                                        @if($item->agency)
                                            <div style="font-size: 0.75rem; color: #36454f; margin-top: 0.25rem;">{{ $item->agency->agency_name ?? '' }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.95rem; border-right: 1px solid rgba(25, 57, 72, 0.1); max-width: 300px;">
                                        <div style="word-wrap: break-word;">{{ $item->subject }}</div>
                                    </td>
                                    <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                        <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: 
                                            @if($item->status === 'PENDING') #f59e0b 
                                            @elseif($item->status === 'RESOLVED') #10b981 
                                            @elseif($item->status === 'IN_PROGRESS') #6366f1
                                            @else #193948 @endif; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            {{ str_replace('_', ' ', $item->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                        <div style="font-weight: 600;">{{ $item->created_at->format('Y-m-d') }}</div>
                                        <div style="font-size: 0.8rem; color: #36454f; margin-top: 0.25rem;">{{ $item->created_at->format('H:i') }}</div>
                                    </td>
                                    <td style="padding: 1.25rem 1rem; text-align: center;">
                                        <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                            <a href="{{ route('superadmin.complaints.show', $item->id) }}" style="padding: 8px 16px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.backgroundColor='#2a4a5a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.backgroundColor='#193948'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                                👁️ Open
                                            </a>
                                            <form action="{{ route('superadmin.complaints.delete', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="padding: 8px 16px; background-color: #E76268; color: white; border-radius: 0.5rem; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.backgroundColor='#d4545a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.backgroundColor='#E76268'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                                    🗑️ Delete
                                                </button>
                                            </form>
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
                    <a href="{{ route('superadmin.complaints.index') }}" class="primary-button" style="padding: 12px 24px; text-decoration: none;">
                        View All Messages
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 10px;">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>


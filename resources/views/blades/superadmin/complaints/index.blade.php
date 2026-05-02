<x-allthepages-layout pageTitle="Reports and Complaints">
    @php
        $totalItems = ($stats['total_complaints'] ?? 0) + ($stats['total_reports'] ?? 0);
        $pendingItems = ($stats['pending_complaints'] ?? 0) + ($stats['pending_reports'] ?? 0);
        $respondedCount = max($totalItems - $pendingItems, 0);
    @endphp

    <div style="padding: 1rem;">
        @if(session('success'))
            <div class="stat-card" style="margin-bottom: 1rem; padding: 0.85rem 1rem; border: 2px solid #10b981;">
                <p style="margin: 0; color: #193948; font-weight: 700;">{{ session('success') }}</p>
            </div>
        @endif

        <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
            <div style="display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap; align-items:flex-start;">
                <div>
                    <h2 style="margin:0; color:#193948; font-size:1.55rem;">Super Admin Case Center</h2>
                    <p style="margin:0.45rem 0 0; color:#36454f;">Inbox for complaints and reports routed to super admin.</p>
                </div>
                <div style="display:flex; gap:0.6rem; flex-wrap:wrap;">
                    <a href="{{ route('superadmin.complaints.index', ['type' => 'complaint']) }}" style="padding:0.55rem 0.9rem; text-decoration:none; border:2px solid #193948; border-radius:10px; color:#193948; font-weight:700; background:{{ request('type') === 'complaint' ? '#ffd9db' : '#fff' }};">Complaints</a>
                    <a href="{{ route('superadmin.reports.index', ['type' => 'report']) }}" style="padding:0.55rem 0.9rem; text-decoration:none; border:2px solid #193948; border-radius:10px; color:#193948; font-weight:700; background:{{ request('type') === 'report' ? '#d8f5e7' : '#fff' }};">Reports</a>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:0.65rem; margin-top:0.9rem;">
                <div style="background:white; border:2px solid #193948; border-radius:10px; padding:0.75rem;">
                    <div style="font-size:1.3rem; font-weight:800; color:#193948;">{{ $totalItems }}</div>
                    <div style="font-size:0.8rem; color:#36454f;">Total Cases</div>
                </div>
                <div style="background:white; border:2px solid #193948; border-radius:10px; padding:0.75rem;">
                    <div style="font-size:1.3rem; font-weight:800; color:#f59e0b;">{{ $pendingItems }}</div>
                    <div style="font-size:0.8rem; color:#36454f;">Pending</div>
                </div>
                <div style="background:white; border:2px solid #193948; border-radius:10px; padding:0.75rem;">
                    <div style="font-size:1.3rem; font-weight:800; color:#10b981;">{{ $respondedCount }}</div>
                    <div style="font-size:0.8rem; color:#36454f;">Handled</div>
                </div>
            </div>
        </div>

        <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
            <form method="GET" action="{{ route('superadmin.complaints.index') }}" style="display:flex; gap:0.55rem; flex-wrap:wrap; align-items:center;">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <label style="color:#193948; font-weight:700;">Status</label>
                <select name="status" style="padding:0.55rem 0.8rem; border:2px solid #193948; border-radius:10px; color:#193948; background:#fff;">
                    <option value="">All</option>
                    <option value="PENDING" @selected(request('status') === 'PENDING')>Pending</option>
                    <option value="IN_PROGRESS" @selected(request('status') === 'IN_PROGRESS')>In Progress</option>
                    <option value="RESOLVED" @selected(request('status') === 'RESOLVED')>Resolved</option>
                </select>
                <button type="submit" style="padding:0.55rem 0.95rem; border:2px solid #193948; border-radius:10px; background:#193948; color:#4FADC0; font-weight:700; cursor:pointer;">Apply</button>
                @if(request('status') || request('type'))
                    <a href="{{ route('superadmin.complaints.index') }}" style="padding:0.55rem 0.95rem; text-decoration:none; border:2px solid #193948; border-radius:10px; background:#fff; color:#193948; font-weight:700;">Clear</a>
                @endif
            </form>
        </div>

        <div class="stat-card" style="padding: 0; overflow:hidden;">
            @if($items->count() > 0)
                <div style="overflow-x:auto;">
                    <table style="width:100%; min-width:920px; border-collapse:collapse;">
                        <thead>
                        <tr style="background:#193948;">
                            <th style="padding:0.85rem; color:#4FADC0; text-align:center;">Type</th>
                            <th style="padding:0.85rem; color:#4FADC0; text-align:center;">From</th>
                            <th style="padding:0.85rem; color:#4FADC0; text-align:center;">Subject</th>
                            <th style="padding:0.85rem; color:#4FADC0; text-align:center;">Status</th>
                            <th style="padding:0.85rem; color:#4FADC0; text-align:center;">Date</th>
                            <th style="padding:0.85rem; color:#4FADC0; text-align:center;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr style="background:{{ $loop->odd ? '#fff' : '#f8f8f8' }};">
                                <td style="padding:0.85rem; text-align:center;">
                                    <span style="padding:0.24rem 0.58rem; border-radius:999px; font-size:0.76rem; font-weight:700; color:#fff; background:{{ $item->type === 'COMPLAINT' ? '#E76268' : '#10b981' }};">
                                        {{ $item->type === 'COMPLAINT' ? 'Complaint' : 'Report' }}
                                    </span>
                                </td>
                                <td style="padding:0.85rem; color:#193948; text-align:center;">
                                    <div style="font-weight:700;">{{ $item->sender?->name ?? ucfirst($item->sender_role ?? 'Unknown') }}</div>
                                    @if($item->agency)
                                        <div style="font-size:0.75rem; color:#36454f;">{{ $item->agency->agency_name ?? '' }}</div>
                                    @endif
                                </td>
                                <td style="padding:0.85rem; color:#193948; text-align:center;">{{ $item->subject }}</td>
                                <td style="padding:0.85rem; text-align:center;">
                                    <span style="padding:0.24rem 0.58rem; border-radius:999px; font-size:0.76rem; font-weight:700; color:#fff; background:
                                    @if($item->status === 'PENDING') #f59e0b @elseif($item->status === 'RESOLVED') #10b981 @else #6366f1 @endif;">
                                        {{ str_replace('_', ' ', $item->status) }}
                                    </span>
                                </td>
                                <td style="padding:0.85rem; color:#36454f; text-align:center;">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                <td style="padding:0.85rem; text-align:center;">
                                    <div style="display:flex; gap:0.4rem; justify-content:center;">
                                        <a href="{{ route('superadmin.complaints.show', $item->id) }}" style="padding:0.38rem 0.7rem; border-radius:10px; text-decoration:none; background:#193948; color:#4FADC0; font-weight:700; font-size:0.82rem;">Open</a>
                                        <form action="{{ route('superadmin.complaints.delete', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="padding:0.38rem 0.7rem; border-radius:10px; border:none; background:#E76268; color:#fff; font-weight:700; font-size:0.82rem; cursor:pointer;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="padding:2.3rem 1rem; text-align:center; color:#36454f;">
                    <div style="font-size:2.2rem;">📭</div>
                    <p style="margin:0.4rem 0 0;">No cases found for current filters.</p>
                </div>
            @endif
        </div>

        @if($items->hasPages())
            <div style="display:flex; justify-content:center; margin-top:1rem;">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>


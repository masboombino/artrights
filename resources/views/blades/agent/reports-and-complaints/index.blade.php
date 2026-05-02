<x-allthepages-layout pageTitle="Reports and Complaints">
    @php
        $totalItems = ($stats['complaints_total'] ?? 0) + ($stats['reports_total'] ?? 0);
        $respondedCount = 0;
        foreach ($items as $item) {
            if ($item->admin_response || $item->gestionnaire_response || $item->super_admin_response) {
                $respondedCount++;
            }
        }
        $pendingCount = ($stats['complaints_pending'] ?? 0) + ($stats['reports_pending'] ?? 0);
    @endphp

    <div style="padding: 1rem;">
        @if(session('success'))
            <div class="stat-card" style="margin-bottom: 1rem; padding: 0.9rem 1rem; border: 2px solid #10b981;">
                <p style="color: #193948; margin: 0; font-weight: 600;">{{ session('success') }}</p>
            </div>
        @endif

        <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
            <div style="display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap; align-items:flex-start;">
                <div>
                    <h2 style="margin:0; color:#193948;">Case Center</h2>
                    <p style="margin:0.4rem 0 0; color:#36454f; font-size:0.9rem;">
                        Agent can submit complaints and reports, and track responses.
                    </p>
                </div>
                <div style="display:flex; gap:0.6rem; flex-wrap:wrap;">
                    <a href="{{ route('agent.complaints.create', ['type' => 'complaint']) }}" class="primary-button" style="background:#E76268; color:white; text-decoration:none;">
                        Submit Complaint
                    </a>
                    <a href="{{ route('agent.complaints.create', ['type' => 'report']) }}" class="primary-button" style="background:#10b981; color:white; text-decoration:none;">
                        Submit Report
                    </a>
                    <a href="{{ route('agent.complaints.sent') }}"
                       style="display:inline-flex; align-items:center; justify-content:center; line-height:1.1; min-height:42px; padding:0.55rem 0.9rem; border:2px solid #193948; border-radius:10px; text-decoration:none; color:#193948; font-weight:700; background:#fff; box-shadow:0 4px 10px rgba(25,57,72,0.16); transition:all 0.2s ease;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 18px rgba(25,57,72,0.24)'; this.style.background='#f9f9f9';"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(25,57,72,0.16)'; this.style.background='#fff';">
                        My Complaints
                    </a>
                    <a href="{{ route('agent.complaints.index', ['type' => 'report']) }}"
                       style="display:inline-flex; align-items:center; justify-content:center; line-height:1.1; min-height:42px; padding:0.55rem 0.9rem; border:2px solid #193948; border-radius:10px; text-decoration:none; color:#193948; font-weight:700; background:#fff; box-shadow:0 4px 10px rgba(25,57,72,0.16); transition:all 0.2s ease;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 18px rgba(25,57,72,0.24)'; this.style.background='#f9f9f9';"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(25,57,72,0.16)'; this.style.background='#fff';">
                        My Reports
                    </a>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:0.7rem; margin-top:1rem;">
                <div style="background:white; border:2px solid #193948; padding:0.8rem; border-radius:0.6rem;">
                    <div style="font-size:1.35rem; font-weight:700; color:#193948;">{{ $totalItems }}</div>
                    <div style="font-size:0.82rem; color:#36454f;">Total Cases</div>
                </div>
                <div style="background:white; border:2px solid #193948; padding:0.8rem; border-radius:0.6rem;">
                    <div style="font-size:1.35rem; font-weight:700; color:#10b981;">{{ $respondedCount }}</div>
                    <div style="font-size:0.82rem; color:#36454f;">Responded</div>
                </div>
                <div style="background:white; border:2px solid #193948; padding:0.8rem; border-radius:0.6rem;">
                    <div style="font-size:1.35rem; font-weight:700; color:#f59e0b;">{{ $pendingCount }}</div>
                    <div style="font-size:0.82rem; color:#36454f;">Pending</div>
                </div>
            </div>
        </div>

        <div class="stat-card" style="padding: 0.5rem; margin-bottom: 1rem; display:flex; gap:0.4rem; flex-wrap:wrap;">
            <a href="{{ route('agent.complaints.index') }}" style="padding:0.6rem 0.9rem; border-radius:0.5rem; text-decoration:none; color:#193948; font-weight:700; background:{{ (request('type') ?? 'all') === 'all' ? '#D6BFBF' : '#fff' }};">
                All
            </a>
            <a href="{{ route('agent.complaints.index', ['type' => 'complaint']) }}" style="padding:0.6rem 0.9rem; border-radius:0.5rem; text-decoration:none; color:#193948; font-weight:700; background:{{ request('type') === 'complaint' ? '#ffd9db' : '#fff' }};">
                Complaints
            </a>
            <a href="{{ route('agent.complaints.index', ['type' => 'report']) }}" style="padding:0.6rem 0.9rem; border-radius:0.5rem; text-decoration:none; color:#193948; font-weight:700; background:{{ request('type') === 'report' ? '#d8f5e7' : '#fff' }};">
                Reports
            </a>
        </div>

        <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
            <form method="GET" action="{{ route('agent.complaints.index') }}" style="display:flex; gap:0.6rem; flex-wrap:wrap; align-items:center;">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <label style="color:#193948; font-weight:700;">Status</label>
                <select name="status" style="padding:0.55rem 0.8rem; border:2px solid #193948; background:white;">
                    <option value="">All Status</option>
                    <option value="PENDING" @selected(request('status') === 'PENDING')>Pending</option>
                    <option value="IN_PROGRESS" @selected(request('status') === 'IN_PROGRESS')>In Progress</option>
                    <option value="RESOLVED" @selected(request('status') === 'RESOLVED')>Resolved</option>
                </select>
                <button type="submit" class="primary-button" style="padding:0.55rem 1rem;">Apply</button>
                @if(request('status') || request('type'))
                    <a href="{{ route('agent.complaints.index') }}" class="secondary-button" style="padding:0.55rem 1rem; text-decoration:none;">Clear</a>
                @endif
            </form>
        </div>

        <div class="stat-card" style="padding:0; overflow:hidden;">
            @if($items->count() > 0)
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; min-width:860px;">
                        <thead>
                            <tr style="background:#193948;">
                                <th style="padding:0.85rem; color:#4FADC0; text-align:left;">Type</th>
                                <th style="padding:0.85rem; color:#4FADC0; text-align:left;">To</th>
                                <th style="padding:0.85rem; color:#4FADC0; text-align:left;">Subject</th>
                                <th style="padding:0.85rem; color:#4FADC0; text-align:left;">Status</th>
                                <th style="padding:0.85rem; color:#4FADC0; text-align:left;">Created</th>
                                <th style="padding:0.85rem; color:#4FADC0; text-align:left;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr style="background:{{ $loop->odd ? '#fff' : '#f8f8f8' }};">
                                    <td style="padding:0.85rem;">
                                        <span style="padding:0.25rem 0.55rem; border-radius:999px; color:white; font-size:0.78rem; background:{{ $item->type === 'COMPLAINT' ? '#E76268' : '#10b981' }};">
                                            {{ $item->type === 'COMPLAINT' ? 'Complaint' : 'Report' }}
                                        </span>
                                    </td>
                                    <td style="padding:0.85rem; color:#193948; font-weight:600;">{{ ucfirst($item->target_role ?? 'admin') }}</td>
                                    <td style="padding:0.85rem; color:#193948;">{{ $item->subject }}</td>
                                    <td style="padding:0.85rem;">
                                        <span style="padding:0.25rem 0.55rem; border-radius:999px; color:white; font-size:0.78rem; background:
                                        @if($item->status === 'PENDING') #f59e0b @elseif($item->status === 'RESOLVED') #10b981 @else #6366f1 @endif;">
                                            {{ str_replace('_', ' ', $item->status) }}
                                        </span>
                                    </td>
                                    <td style="padding:0.85rem; color:#36454f;">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                    <td style="padding:0.85rem; display:flex; gap:0.4rem;">
                                        <a href="{{ route('agent.complaints.show', $item->id) }}" style="padding:0.35rem 0.7rem; border-radius:0.45rem; text-decoration:none; background:#193948; color:#4FADC0; font-weight:700; font-size:0.82rem;">Open</a>
                                        @if($item->type === 'COMPLAINT')
                                            <form action="{{ route('agent.complaints.delete', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="padding:0.35rem 0.7rem; border:0; border-radius:0.45rem; background:#E76268; color:white; font-weight:700; font-size:0.82rem; cursor:pointer;">Delete</button>
                                            </form>
                                        @endif
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

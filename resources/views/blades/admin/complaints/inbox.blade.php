<x-allthepages-layout pageTitle="Complaints Inbox">
    <div style="padding: 1rem;">
        <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
            <h2 style="margin:0; color:#193948;">Complaints Inbox</h2>
            <p style="margin:0.4rem 0 0; color:#36454f;">Complaints sent to admin.</p>
        </div>

        <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
            <form method="GET" action="{{ route('admin.complaints.inbox') }}" style="display:flex; gap:0.5rem; flex-wrap:wrap; align-items:center;">
                <label style="font-weight:700; color:#193948;">Status</label>
                <select name="status" style="padding:0.55rem 0.8rem; border:2px solid #193948; border-radius:10px;">
                    <option value="">All</option>
                    <option value="PENDING" @selected(request('status') === 'PENDING')>Pending</option>
                    <option value="IN_PROGRESS" @selected(request('status') === 'IN_PROGRESS')>In Progress</option>
                    <option value="RESOLVED" @selected(request('status') === 'RESOLVED')>Resolved</option>
                </select>
                <button type="submit" style="padding:0.55rem 0.9rem; border:none; border-radius:10px; background:#193948; color:#4FADC0; font-weight:700;">Apply</button>
            </form>
        </div>

        <div class="stat-card" style="padding:0; overflow:hidden;">
            @if($items->count())
                <div style="overflow-x:auto;">
                    <table style="width:100%; min-width:880px; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#193948;">
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">From</th>
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Subject</th>
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Status</th>
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Response</th>
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Date</th>
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr style="background:{{ $loop->odd ? '#fff' : '#f8f8f8' }};">
                                    <td style="padding:0.8rem;">{{ $item->sender?->name ?? ucfirst($item->sender_role ?? 'Unknown') }}</td>
                                    <td style="padding:0.8rem;">{{ $item->subject }}</td>
                                    <td style="padding:0.8rem;">{{ str_replace('_', ' ', $item->status) }}</td>
                                    <td style="padding:0.8rem;">
                                        @if($item->admin_response)
                                            <span style="color:#10b981; font-weight:700;">Responded</span>
                                        @else
                                            <span style="color:#f59e0b; font-weight:700;">Waiting</span>
                                        @endif
                                    </td>
                                    <td style="padding:0.8rem;">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                    <td style="padding:0.8rem;">
                                        <a href="{{ route('admin.complaints.show', $item->id) }}" style="padding:0.35rem 0.7rem; border-radius:10px; text-decoration:none; background:#193948; color:#4FADC0; font-weight:700;">Open</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding:0.8rem;">{{ $items->links() }}</div>
            @else
                <div style="padding:1.5rem; text-align:center; color:#36454f;">No complaints found.</div>
            @endif
        </div>
    </div>
</x-allthepages-layout>

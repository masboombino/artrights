<x-allthepages-layout pageTitle="Complaints Sent">
    <div style="padding: 1rem;">
        <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
            <div style="display:flex; justify-content:space-between; gap:0.8rem; flex-wrap:wrap; align-items:flex-start;">
                <div>
                    <h2 style="margin:0; color:#193948;">Complaints Sent</h2>
                    <p style="margin:0.4rem 0 0; color:#36454f;">Track what you sent and what they replied.</p>
                </div>
                <a href="{{ route('admin.complaints.create', ['type' => 'complaint']) }}" style="display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0.55rem 0.9rem; border-radius:10px; text-decoration:none; background:#E76268; color:#fff; font-weight:700;">
                    New Complaint
                </a>
            </div>
        </div>

        <div class="stat-card" style="padding:0; overflow:hidden;">
            @if($items->count())
                <div style="overflow-x:auto;">
                    <table style="width:100%; min-width:900px; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#193948;">
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">To</th>
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Subject</th>
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Status</th>
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Reply</th>
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Date</th>
                                <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr style="background:{{ $loop->odd ? '#fff' : '#f8f8f8' }};">
                                    <td style="padding:0.8rem;">{{ $item->targetUser->name ?? ucfirst($item->target_role ?? 'Unknown') }}</td>
                                    <td style="padding:0.8rem;">{{ $item->subject }}</td>
                                    <td style="padding:0.8rem;">
                                        <span style="padding:0.24rem 0.58rem; border-radius:999px; font-size:0.76rem; font-weight:700; color:#fff; background:
                                            @if($item->status === 'PENDING') #f59e0b
                                            @elseif($item->status === 'RESOLVED') #10b981
                                            @else #6366f1 @endif;">
                                            {{ str_replace('_', ' ', $item->status) }}
                                        </span>
                                    </td>
                                    <td style="padding:0.8rem;">
                                        @if($item->gestionnaire_response || $item->super_admin_response)
                                            <span style="color:#10b981; font-weight:700;">Received</span>
                                        @else
                                            <span style="color:#f59e0b; font-weight:700;">No reply yet</span>
                                        @endif
                                    </td>
                                    <td style="padding:0.8rem;">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                    <td style="padding:0.8rem;">
                                        <a href="{{ route('admin.complaints.show', $item->id) }}" style="display:inline-flex; align-items:center; justify-content:center; min-height:34px; padding:0.35rem 0.7rem; border-radius:10px; text-decoration:none; background:#193948; color:#4FADC0; font-weight:700;">Open</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding:0.8rem;">{{ $items->links() }}</div>
            @else
                <div style="padding:1.5rem; text-align:center; color:#36454f;">No sent complaints found.</div>
            @endif
        </div>
    </div>
</x-allthepages-layout>

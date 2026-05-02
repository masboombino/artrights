<x-allthepages-layout pageTitle="Reports">
    <div style="padding:1rem;">
        <div class="stat-card" style="padding:1rem; margin-bottom:1rem;">
            <h2 style="margin:0; color:#193948;">Reports</h2>
            <p style="margin:0.4rem 0 0; color:#36454f;">All reports received by admin.</p>
        </div>
        <div class="stat-card" style="padding:0; overflow:hidden;">
            @if($items->count())
                <div style="overflow-x:auto;">
                    <table style="width:100%; min-width:860px; border-collapse:collapse;">
                        <thead><tr style="background:#193948;">
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">From</th>
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Subject</th>
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Status</th>
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Date</th>
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Action</th>
                        </tr></thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr style="background:{{ $loop->odd ? '#fff' : '#f8f8f8' }};">
                                <td style="padding:0.8rem;">{{ $item->sender?->name ?? ucfirst($item->sender_role ?? 'Unknown') }}</td>
                                <td style="padding:0.8rem;">{{ $item->subject }}</td>
                                <td style="padding:0.8rem;">{{ str_replace('_', ' ', $item->status) }}</td>
                                <td style="padding:0.8rem;">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                <td style="padding:0.8rem;"><a href="{{ route('admin.reports.show', $item->id) }}" style="padding:0.35rem 0.7rem; border-radius:10px; text-decoration:none; background:#193948; color:#4FADC0; font-weight:700;">Open</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding:0.8rem;">{{ $items->links() }}</div>
            @else
                <div style="padding:1.5rem; text-align:center; color:#36454f;">No reports found.</div>
            @endif
        </div>
    </div>
</x-allthepages-layout>

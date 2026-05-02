<x-allthepages-layout pageTitle="Complaints Sent">
    <div style="padding:1rem;">
        <div class="stat-card" style="padding:1rem; margin-bottom:1rem;">
            <h2 style="margin:0; color:#193948;">Complaints Sent</h2>
            <p style="margin:0.4rem 0 0; color:#36454f;">Complaints sent by agent with reply status.</p>
        </div>
        <div class="stat-card" style="padding:0; overflow:hidden;">
            @if($complaints->count())
                <div style="overflow-x:auto;">
                    <table style="width:100%; min-width:900px; border-collapse:collapse;">
                        <thead><tr style="background:#193948;">
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">To</th>
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Subject</th>
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Status</th>
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Reply</th>
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Date</th>
                            <th style="padding:0.8rem; color:#4FADC0; text-align:left;">Action</th>
                        </tr></thead>
                        <tbody>
                        @foreach($complaints as $complaint)
                            @php
                                $targetResponseField = $complaint->target_role === 'gestionnaire' ? 'gestionnaire_response' : 'admin_response';
                            @endphp
                            <tr style="background:{{ $loop->odd ? '#fff' : '#f8f8f8' }};">
                                <td style="padding:0.8rem;">{{ $complaint->targetUser->name ?? ucfirst($complaint->target_role ?? 'Unknown') }}</td>
                                <td style="padding:0.8rem;">{{ $complaint->subject }}</td>
                                <td style="padding:0.8rem;">{{ str_replace('_', ' ', $complaint->status) }}</td>
                                <td style="padding:0.8rem;">
                                    @if($complaint->{$targetResponseField})
                                        <span style="color:#10b981; font-weight:700;">Received</span>
                                    @else
                                        <span style="color:#f59e0b; font-weight:700;">No reply yet</span>
                                    @endif
                                </td>
                                <td style="padding:0.8rem;">{{ $complaint->created_at->format('Y-m-d H:i') }}</td>
                                <td style="padding:0.8rem;"><a href="{{ route('agent.complaints.show', $complaint->id) }}" style="padding:0.35rem 0.7rem; border-radius:10px; text-decoration:none; background:#193948; color:#4FADC0; font-weight:700;">Open</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding:0.8rem;">{{ $complaints->links() }}</div>
            @else
                <div style="padding:1.5rem; text-align:center; color:#36454f;">No sent complaints found.</div>
            @endif
        </div>
    </div>
</x-allthepages-layout>

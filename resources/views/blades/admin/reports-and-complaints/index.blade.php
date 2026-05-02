<x-allthepages-layout pageTitle="Reports and Complaints">
    @php
        $totalItems = ($stats['complaints_total'] ?? 0) + ($stats['reports_total'] ?? 0);
    @endphp
    <div style="padding: 1rem;">
        <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
            <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:0.8rem;">
                <div>
                    <h2 style="margin:0; color:#193948;">Admin Case Center</h2>
                    <p style="margin:0.35rem 0 0; color:#36454f;">Admin can submit and respond to complaints and reports.</p>
                </div>
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                    <a href="{{ route('admin.complaints.create', ['type' => 'complaint']) }}" class="primary-button" style="background:#E76268; color:white; text-decoration:none;">Submit Complaint</a>
                    <a href="{{ route('admin.complaints.create', ['type' => 'report']) }}" class="primary-button" style="background:#10b981; color:white; text-decoration:none;">Submit Report</a>
                    <a href="{{ route('admin.complaints.sent') }}"
                       style="display:inline-flex; align-items:center; justify-content:center; line-height:1.1; min-height:42px; padding:0.55rem 0.9rem; border:2px solid #193948; border-radius:10px; text-decoration:none; color:#193948; font-weight:700; background:#fff; box-shadow:0 4px 10px rgba(25,57,72,0.16); transition:all 0.2s ease;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 18px rgba(25,57,72,0.24)'; this.style.background='#f9f9f9';"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(25,57,72,0.16)'; this.style.background='#fff';">My Complaints</a>
                    <a href="{{ route('admin.reports.sent') }}"
                       style="display:inline-flex; align-items:center; justify-content:center; line-height:1.1; min-height:42px; padding:0.55rem 0.9rem; border:2px solid #193948; border-radius:10px; text-decoration:none; color:#193948; font-weight:700; background:#fff; box-shadow:0 4px 10px rgba(25,57,72,0.16); transition:all 0.2s ease;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 18px rgba(25,57,72,0.24)'; this.style.background='#f9f9f9';"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(25,57,72,0.16)'; this.style.background='#fff';">My Reports</a>
                </div>
            </div>
            <div style="margin-top:0.8rem; color:#193948; font-weight:700;">Total Cases: {{ $totalItems }}</div>
        </div>
        <div class="stat-card" style="padding: 1rem;">
            <div style="overflow-x:auto;">
                <table style="width:100%; min-width:830px; border-collapse:collapse;">
                    <thead><tr style="background:#193948;">
                        <th style="padding:0.7rem; color:#4FADC0; text-align:left;">Type</th>
                        <th style="padding:0.7rem; color:#4FADC0; text-align:left;">From</th>
                        <th style="padding:0.7rem; color:#4FADC0; text-align:left;">Subject</th>
                        <th style="padding:0.7rem; color:#4FADC0; text-align:left;">Status</th>
                        <th style="padding:0.7rem; color:#4FADC0; text-align:left;">Date</th>
                        <th style="padding:0.7rem; color:#4FADC0; text-align:left;">Actions</th>
                    </tr></thead>
                    <tbody>
                    @forelse($items as $item)
                        <tr style="background:{{ $loop->odd ? '#fff' : '#f8f8f8' }};">
                            <td style="padding:0.7rem;">{{ $item->type }}</td>
                            <td style="padding:0.7rem;">{{ $item->sender?->name ?? ucfirst($item->sender_role ?? 'Unknown') }}</td>
                            <td style="padding:0.7rem;">{{ $item->subject }}</td>
                            <td style="padding:0.7rem;">{{ str_replace('_', ' ', $item->status) }}</td>
                            <td style="padding:0.7rem;">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                            <td style="padding:0.7rem;">
                                <a href="{{ route('admin.view-complaint', $item->id) }}" style="display:inline-block; padding:0.3rem 0.6rem; border-radius:0.45rem; text-decoration:none; background:#193948; color:#4FADC0; font-weight:700;">Open</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="padding:1rem; text-align:center; color:#36454f;">No items found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-allthepages-layout>

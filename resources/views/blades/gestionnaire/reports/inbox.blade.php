<x-allthepages-layout pageTitle="Reports Inbox">
    <div style="padding: 5px; margin: 5px;">
        <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;">
            <h2 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Reports Inbox</h2>
            <p style="color: #36454f; font-size: 0.9rem;">Reports sent to you</p>
        </div>

        @if($reports->count() > 0)
            <div class="stat-card" style="padding: 0;">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #193948;">
                                <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 600;">From</th>
                                <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 600;">Subject</th>
                                <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 600;">Status</th>
                                <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 600;">Date</th>
                                <th style="color: #4FADC0; padding: 1rem; text-align: center; font-size: 0.85rem; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr style="border-top: 1px solid rgba(25, 57, 72, 0.1);">
                                    <td style="color: #193948; padding: 1rem; text-align: center;">
                                        {{ $report->sender?->name ?? 'Unknown' }}
                                    </td>
                                    <td style="color: #193948; padding: 1rem; text-align: center;">
                                        <div style="font-weight: 600;">{{ $report->subject }}</div>
                                        <div style="font-size: 0.75rem; color: #36454f;">
                                            {{ \Illuminate\Support\Str::limit($report->message, 50) }}
                                        </div>
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <span style="padding: 4px 12px; font-size: 0.75rem; font-weight: 600; background-color: 
                                            @if($report->status === 'PENDING') #f59e0b 
                                            @elseif($report->status === 'RESOLVED') #10b981 
                                            @else #193948 @endif; color: white;">
                                            {{ str_replace('_', ' ', $report->status) }}
                                        </span>
                                    </td>
                                    <td style="color: #193948; padding: 1rem; text-align: center;">
                                        {{ $report->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <a href="{{ route('gestionnaire.reports.show', $report->id) }}" class="primary-button" style="padding: 6px 12px; font-size: 0.85rem;">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding: 1rem;">
                    {{ $reports->links() }}
                </div>
            </div>
        @else
            <div class="stat-card" style="padding: 2rem; text-align: center;">
                <p style="color: #36454f;">No reports found</p>
            </div>
        @endif
    </div>
</x-allthepages-layout>


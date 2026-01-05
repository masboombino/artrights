<x-allthepages-layout pageTitle="Wallet Recharge Requests">
    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 rounded-lg border-2 mb-4" style="background-color: #d1fae5; border-color: #10b981;">
                <p class="font-semibold" style="color: #065f46;">{{ session('success') }}</p>
            </div>
        @endif

        <h1 class="text-3xl font-bold" style="color: #D6BFBF;">Wallet Recharge Requests</h1>

        <!-- Pending Requests -->
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-4" style="color: #193948;">Pending Requests</h2>
            @if($pendingRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full" style="border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #193948;">
                                <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Artist</th>
                                <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Amount</th>
                                <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Payment Method</th>
                                <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Reference</th>
                                <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Date</th>
                                <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRequests as $req)
                                <tr style="border-top: 1px solid rgba(0,0,0,0.1);">
                                    <td class="text-center text-sm" style="color: #193948; padding: 1rem 1.5rem;">
                                        {{ $req->artist->user->name ?? 'N/A' }}<br>
                                        <span class="text-xs" style="color: #6b7280;">{{ $req->artist->stage_name ?? '' }}</span>
                                    </td>
                                    <td class="text-center text-sm font-semibold" style="color: #193948; padding: 1rem 1.5rem;">{{ number_format($req->amount, 2) }} DZD</td>
                                    <td class="text-center text-sm" style="color: #193948; padding: 1rem 1.5rem;">{{ $req->payment_method === 'CHEQUE' ? 'Cheque' : 'Bank Transfer' }}</td>
                                    <td class="text-center text-sm" style="color: #193948; padding: 1rem 1.5rem;">{{ $req->transaction_reference }}</td>
                                    <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $req->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="text-center text-sm whitespace-nowrap" style="padding: 1rem 1.5rem;">
                                        <a href="{{ route('admin.wallet-recharge.show', $req->id) }}" class="inline-block rounded text-sm transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.5rem 1rem;">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-sm" style="color: #193948; padding: 1.5rem;">No pending requests</p>
            @endif
        </div>

        <!-- Approved Requests -->
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-4" style="color: #193948;">Recently Approved</h2>
            @if($approvedRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full" style="border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #193948;">
                                <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Artist</th>
                                <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Amount</th>
                                <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Approved By</th>
                                <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedRequests as $req)
                                <tr style="border-top: 1px solid rgba(0,0,0,0.1);">
                                    <td class="text-center text-sm" style="color: #193948; padding: 1rem 1.5rem;">{{ $req->artist->user->name ?? 'N/A' }}</td>
                                    <td class="text-center text-sm font-semibold" style="color: #193948; padding: 1rem 1.5rem;">{{ number_format($req->amount, 2) }} DZD</td>
                                    <td class="text-center text-sm" style="color: #193948; padding: 1rem 1.5rem;">{{ $req->approver->name ?? 'N/A' }}</td>
                                    <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $req->approved_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-sm" style="color: #193948; padding: 1.5rem;">No approved requests</p>
            @endif
        </div>
    </div>
</x-allthepages-layout>


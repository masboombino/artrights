<x-allthepages-layout pageTitle="Manage PVs">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold" style="color: #D6BFBF;">PVs/Transactions</h1>
            <a href="{{ route('superadmin.dashboard') }}" class="rounded transition hover:opacity-90 whitespace-nowrap" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem;">
                <span>Back to Dashboard</span>
            </a>
        </div>

        <div class="rounded-lg shadow" style="background-color: #F3EBDD;">
            <div style="overflow-x: auto; width: 100%;">
                <table class="w-full" style="border-collapse: collapse; min-width: 800px;">
                    <thead>
                        <tr style="background-color: #193948;">
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">PV ID</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Agency</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Agent</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Shop Name</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Date</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Status</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Payment</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Amount</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Transactions</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pvs as $pv)
                            <tr style="border-top: 1px solid rgba(0,0,0,0.1);">
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">#{{ $pv->id }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $pv->agency->agency_name ?? 'N/A' }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $pv->agent->user->name ?? 'N/A' }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $pv->shop_name ?? 'N/A' }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $pv->created_at->format('Y-m-d') }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">
                                    <span class="px-2 py-1 rounded text-xs" style="background-color: #193948; color: #4FADC0;">
                                        {{ $pv->status }}
                                    </span>
                                </td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">
                                    {{ $pv->payment_method ?? 'N/A' }} / {{ $pv->payment_status }}
                                </td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ number_format($pv->total_amount, 2) }} DZD</td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $pv->transactions->count() }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="padding: 1rem 1.5rem;">
                                    <a href="{{ route('superadmin.view-pv', $pv) }}" class="inline-block rounded text-sm transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.5rem 1rem;">
                                        <span>View</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-sm" style="color: #193948; padding: 1.5rem;">No PVs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pvs->hasPages())
            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid rgba(25, 57, 72, 0.1);">
                {{ $pvs->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>


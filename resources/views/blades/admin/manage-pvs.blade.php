<x-allthepages-layout pageTitle="Manage PVs">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="page-container" style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Shop</th>
                        <th>Inspection Date</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pvs as $pv)
                        <tr>
                            <td>{{ $pv->agent->user->name ?? 'N/A' }}</td>
                            <td>{{ $pv->shop_name }}</td>
                            <td>{{ $pv->date_of_inspection?->format('d/m/Y H:i') }}</td>
                            <td><span class="status-badge">{{ $pv->status }}</span></td>
                            <td><span class="status-badge">{{ $pv->payment_status }}</span></td>
                            <td>{{ number_format($pv->total_amount, 2) }} DZD</td>
                            <td>
                                <a href="{{ route('admin.view-pv', $pv) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No PVs found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pvs->hasPages())
            <div style="margin: 5px; padding: 5px;">
                {{ $pvs->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>

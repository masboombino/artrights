<x-allthepages-layout pageTitle="Manage PVs">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Filters -->
        <div class="stat-card" style="padding: 1rem; margin-bottom: 10px;">
            <form method="GET" action="{{ route('admin.manage-pvs') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <label style="color: #193948; font-weight: 600; font-size: 0.9rem;">Status:</label>
                <select name="status" style="padding: 8px 16px; border: 2px solid #193948; color: #193948; background-color: white; font-size: 0.9rem; border-radius: 0.5rem;">
                    <option value="">All Status</option>
                    <option value="OPEN" {{ request('status') === 'OPEN' ? 'selected' : '' }}>Open</option>
                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="CLOSED" {{ request('status') === 'CLOSED' ? 'selected' : '' }}>Closed</option>
                </select>
                <label style="color: #193948; font-weight: 600; font-size: 0.9rem;">Payment Status:</label>
                <select name="payment_status" style="padding: 8px 16px; border: 2px solid #193948; color: #193948; background-color: white; font-size: 0.9rem; border-radius: 0.5rem;">
                    <option value="">All Payment Status</option>
                    <option value="PENDING" {{ request('payment_status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="VALIDATED" {{ request('payment_status') === 'VALIDATED' ? 'selected' : '' }}>Validated</option>
                    <option value="PAID" {{ request('payment_status') === 'PAID' ? 'selected' : '' }}>Paid</option>
                </select>
                <label style="color: #193948; font-weight: 600; font-size: 0.9rem;">Finalized:</label>
                <select name="finalized" style="padding: 8px 16px; border: 2px solid #193948; color: #193948; background-color: white; font-size: 0.9rem; border-radius: 0.5rem;">
                    <option value="">All</option>
                    <option value="yes" {{ request('finalized') === 'yes' ? 'selected' : '' }}>Finalized</option>
                    <option value="no" {{ request('finalized') === 'no' ? 'selected' : '' }}>Not Finalized</option>
                </select>
                <button type="submit" class="primary-button" style="padding: 8px 20px; font-size: 0.9rem;">
                    Apply Filter
                </button>
                @if(request('status') || request('payment_status') || request('finalized'))
                    <a href="{{ route('admin.manage-pvs') }}" class="secondary-button" style="padding: 8px 20px; font-size: 0.9rem; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </form>
        </div>

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

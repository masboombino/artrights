<x-allthepages-layout pageTitle="Manage PVs">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Filters -->
        <div class="stat-card" style="padding: 1rem; margin-bottom: 10px;">
            <form method="GET" action="{{ route('gestionnaire.pvs.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <label style="color: #193948; font-weight: 600; font-size: 0.9rem;">Filter:</label>
                <select name="filter" style="padding: 8px 16px; border: 2px solid #193948; color: #193948; background-color: white; font-size: 0.9rem; border-radius: 0.5rem;">
                    <option value="">All PVs</option>
                    <option value="open" {{ request('filter') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="pending_payment" {{ request('filter') === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="awaiting_release" {{ request('filter') === 'awaiting_release' ? 'selected' : '' }}>Awaiting Release</option>
                </select>
                <button type="submit" class="primary-button" style="padding: 8px 20px; font-size: 0.9rem;">
                    Apply Filter
                </button>
                @if(request('filter'))
                    <a href="{{ route('gestionnaire.pvs.index') }}" class="secondary-button" style="padding: 8px 20px; font-size: 0.9rem; text-decoration: none;">
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
                        <th>Type</th>
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
                            <td>{{ $pv->shop_type }}</td>
                            <td>{{ $pv->date_of_inspection?->format('d/m/Y H:i') }}</td>
                            <td><span class="status-badge">{{ $pv->status }}</span></td>
                            <td><span class="status-badge">{{ $pv->payment_status }}</span></td>
                            <td>{{ number_format($pv->total_amount, 2) }} DZD</td>
                            <td>
                                <a href="{{ route('gestionnaire.pvs.show', $pv) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No PVs found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-allthepages-layout>

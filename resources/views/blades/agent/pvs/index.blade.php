<x-allthepages-layout pageTitle="My PVs">
    <div style="padding: 5px; margin: 5px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px; padding: 5px;">
            <h1 style="color: #F3EBDD; font-size: 1.75rem; font-weight: 700; margin: 0;">
                All Reports (PVs)
            </h1>
            <a href="{{ route('agent.pvs.create') }}" class="primary-button">
                Create New PV
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Filters -->
        <div class="stat-card" style="padding: 1rem; margin-bottom: 10px;">
            <form method="GET" action="{{ route('agent.pvs.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <label style="color: #193948; font-weight: 600; font-size: 0.9rem;">Filter by Status:</label>
                <select name="status" style="padding: 8px 16px; border: 2px solid #193948; color: #193948; background-color: white; font-size: 0.9rem; border-radius: 0.5rem;">
                    <option value="">All Status</option>
                    <option value="OPEN" {{ request('status') === 'OPEN' ? 'selected' : '' }}>Open</option>
                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="CLOSED" {{ request('status') === 'CLOSED' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="primary-button" style="padding: 8px 20px; font-size: 0.9rem;">
                    Apply Filter
                </button>
                @if(request('status'))
                    <a href="{{ route('agent.pvs.index') }}" class="secondary-button" style="padding: 8px 20px; font-size: 0.9rem; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if($pvs->count() > 0)
            <div style="background-color: #F3EBDD; border-radius: 1rem; margin: 5px; padding: 1.5rem; overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Shop Name</th>
                            <th>Shop Type</th>
                            <th>Inspection Date</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Total Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pvs as $pv)
                            <tr>
                                <td style="font-weight: 600; color: #193948;">{{ $pv->shop_name }}</td>
                                <td style="color: #193948;">{{ $pv->shop_type }}</td>
                                <td style="color: #193948;">
                                    {{ $pv->date_of_inspection ? $pv->date_of_inspection->format('d/m/Y H:i') : 'Not set' }}
                                </td>
                                <td>
                                    <span class="status-badge">{{ $pv->status }}</span>
                                </td>
                                <td style="color: #193948;">{{ $pv->payment_method ?? 'N/A' }}</td>
                                <td style="color: #193948;">
                                    <span style="background-color: {{ $pv->payment_status === 'PAID' ? '#10b981' : ($pv->payment_status === 'PENDING' ? '#f59e0b' : '#E76268') }}; color: white; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600;">
                                        {{ $pv->payment_status }}
                                    </span>
                                </td>
                                <td style="color: #193948; font-weight: 700;">{{ number_format($pv->total_amount, 2) }} DZD</td>
                                <td>
                                    <a href="{{ route('agent.pvs.show', $pv) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="background-color: #F3EBDD; border-radius: 1rem; margin: 5px; padding: 3rem 1.5rem; text-align: center;">
                <p style="color: #193948; font-size: 1.2rem; font-weight: 600; margin: 0 0 1rem 0;">
                    No PVs yet
                </p>
                <p style="color: #193948; font-size: 1rem; margin: 0 0 1.5rem 0; opacity: 0.8;">
                    Start by creating your first inspection report (PV).
                </p>
                <a href="{{ route('agent.pvs.create') }}" class="primary-button">
                    Create Your First PV
                </a>
            </div>
        @endif
    </div>
</x-allthepages-layout>

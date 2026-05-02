<x-allthepages-layout pageTitle="Manage Agencies">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="mb-4" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="text-2xl font-bold" style="color: #D6BFBF;">Agencies ({{ $agencies->count() }} / 70 Wilayas)</h2>
            <a href="{{ route('superadmin.create-agency') }}" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem; text-decoration: none;">
                + Create New Agency
            </a>
        </div>

        <div class="page-container">
            <div style="overflow-x: auto; width: 100%;">
                <table class="data-table" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th>Wilaya</th>
                            <th>Agency Name</th>
                            <th>Bank Account Number</th>
                            <th>Admin</th>
                            <th>Gestionnaires</th>
                            <th>Agents</th>
                            <th>Artists</th>
                            <th>Wallet Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agencies as $agency)
                            <tr>
                                <td>{{ $agency->wilaya }}</td>
                                <td>{{ $agency->agency_name }}</td>
                                <td style="font-family: monospace;">{{ $agency->bank_account_number ?? 'N/A' }}</td>
                                <td>{{ $agency->admin->name ?? 'Not assigned' }}</td>
                                <td>{{ $agency->gestionnaires()->count() }}</td>
                                <td>{{ $agency->agents()->count() }}</td>
                                <td>{{ $agency->artists()->count() }}</td>
                                <td>{{ number_format($agency->wallet->balance ?? 0, 2) }} DZD</td>
                                <td>
                                    <a href="{{ route('superadmin.show-agency', $agency) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">No agencies found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-allthepages-layout>

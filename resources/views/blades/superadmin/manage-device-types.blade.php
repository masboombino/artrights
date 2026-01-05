<x-allthepages-layout pageTitle="Manage Device Types">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div style="margin-bottom: 1rem; padding: 5px;">
            <a href="{{ route('superadmin.create-device-type') }}" class="primary-button">
                Create Device Type
            </a>
        </div>

        <div class="page-container">
            <div style="overflow-x: auto; width: 100%;">
                <table class="data-table" style="min-width: 600px;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Compensation Amount</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deviceTypes as $device)
                            <tr>
                                <td>{{ $device->name }}</td>
                                <td>{{ Str::limit($device->description ?? 'N/A', 50) }}</td>
                                <td>{{ number_format($device->compensation_amount, 2) }} DZD</td>
                                <td>{{ $device->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div style="display: flex; gap: 5px; flex-wrap: wrap; justify-content: center;">
                                        <a href="{{ route('superadmin.edit-device-type', $device) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                            Edit
                                        </a>
                                        <form action="{{ route('superadmin.delete-device-type', $device) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="danger-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No device types found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($deviceTypes->hasPages())
            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid rgba(25, 57, 72, 0.1);">
                {{ $deviceTypes->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>

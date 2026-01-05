<x-allthepages-layout pageTitle="Reports and Complaints">
    <div style="padding: 5px; margin: 5px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px; padding: 5px;">
            <h1 style="color: #D6BFBF; font-size: 1.75rem; font-weight: 700; margin: 0;">
                Reports and Complaints
            </h1>
            <a href="{{ route('admin.create-superadmin-complaint') }}" class="primary-button" style="background-color: #10b981;">
                Submit Complaint
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success" style="margin-bottom: 1.5rem;">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error" style="margin-bottom: 1.5rem;">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div style="background-color: #F3EBDD; border-radius: 1rem; margin: 5px; padding: 1.5rem; overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $complaint)
                        <tr>
                            <td style="font-weight: 600; color: #193948;">{{ $complaint->subject }}</td>
                            <td>
                                <span class="status-badge" style="background-color: {{ $complaint->status === 'RESOLVED' ? '#10b981' : ($complaint->status === 'PENDING' ? '#E76268' : '#193948') }};">
                                    {{ $complaint->status }}
                                </span>
                            </td>
                            <td style="white-space: nowrap; color: #193948;">{{ $complaint->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('superadmin.view-admin-complaint', $complaint->id) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    Open Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #193948; opacity: 0.5;">
                                        <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.89 22 5.99 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16 13H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16 17H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10 9H9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p style="color: #193948; font-size: 1rem; font-weight: 600;">No complaints found</p>
                                    <p style="color: #193948; font-size: 0.9rem; opacity: 0.7;">You haven't submitted any complaints yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if(isset($complaints) && method_exists($complaints, 'hasPages') && $complaints->hasPages())
                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid rgba(25, 57, 72, 0.1);">
                    {{ $complaints->links() }}
                </div>
            @endif
        </div>
    </div>
</x-allthepages-layout>

<x-allthepages-layout pageTitle="Manage Users">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="page-container" style="overflow-x: auto;">
            <h2 style="color: #D6BFBF; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Pending Artist Approvals</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Agency</th>
                        <th>Bank Account Number</th>
                        <th>Birth Date</th>
                        <th>Registered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingArtists as $artist)
                        <tr>
                            <td>{{ $artist->user->name }}</td>
                            <td>{{ $artist->user->email }}</td>
                            <td>{{ $artist->user->phone ?? 'N/A' }}</td>
                            <td>{{ $artist->agency ? $artist->agency->agency_name . ' - ' . $artist->agency->wilaya : 'N/A' }}</td>
                            <td style="font-family: monospace;">{{ $artist->bank_account_number ?? 'N/A' }}</td>
                            <td>{{ $artist->birth_date ?? 'N/A' }}</td>
                            <td>{{ $artist->user->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap; justify-content: center;">
                                    <a href="{{ route('admin.view-artist', $artist->id) }}" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        View
                                    </a>
                                    <form action="{{ route('admin.approve-artist', $artist->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="primary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem; background-color: #10b981;">
                                            Approve
                                        </button>
                                    </form>
                                    <button type="button" onclick="openRejectModal({{ $artist->id }})" class="danger-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No pending users</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; justify-content: center; align-items: center;">
        <div style="background: #F3EBDD; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; border: 3px solid #E76268; box-shadow: 0 8px 24px rgba(0,0,0,0.3);">
            <h3 style="color: #E76268; font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">Reject Artist Account</h3>
            <p style="color: #36454f; margin-bottom: 1.5rem;">Are you sure you want to reject this artist? This will permanently delete their account. <strong style="color: #E76268;">You must provide a reason for rejection.</strong></p>
            
            <form id="rejectForm" method="POST" style="margin: 0;">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label for="rejection_reason" style="display: block; color: #193948; font-weight: 600; margin-bottom: 0.5rem;">
                        Rejection Reason <span style="color: #E76268;">*</span>
                    </label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="5" required minlength="10" maxlength="1000" style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 8px; font-family: inherit; resize: vertical; background-color: #ffffff;" placeholder="Please explain why this account is being rejected (minimum 10 characters)..."></textarea>
                    <small style="color: #36454f; font-size: 0.85rem; display: block; margin-top: 0.5rem;">This reason will be sent to the user via email. Minimum 10 characters required.</small>
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="closeRejectModal()" class="rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #36454f; color: #4FADC0; padding: 0.75rem 1.5rem; font-weight: 600; border: none; cursor: pointer;">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #E76268; color: #193948; padding: 0.75rem 1.5rem; font-weight: 600; border: none; cursor: pointer;">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openRejectModal(artistId) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            form.action = '{{ route("admin.reject-artist", ":id") }}'.replace(':id', artistId);
            modal.style.display = 'flex';
        }
        
        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            const textarea = document.getElementById('rejection_reason');
            modal.style.display = 'none';
            textarea.value = '';
        }
        
        // Close modal when clicking outside
        document.getElementById('rejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('rejectModal');
                if (modal && modal.style.display === 'flex') {
                    closeRejectModal();
                }
            }
        });
    </script>
</x-allthepages-layout>

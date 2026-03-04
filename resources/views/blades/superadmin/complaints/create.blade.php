<x-allthepages-layout :pageTitle="ucfirst($type) . ' Create'">
    <div style="padding: 5px; margin: 5px;">
        <div class="stat-card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #193948; margin: 0;"> Submit New {{ ucfirst($type) }}</h2>
                <a href="{{ $type === 'report' ? route('superadmin.reports.index') : route('superadmin.complaints.index') }}" class="secondary-button" style="text-decoration: none;">
                    ← Back to List
                </a>
            </div>

            @if($errors->any())
                <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $type === 'report' ? route('superadmin.reports.store', ['type' => 'report']) : route('superadmin.complaints.store', ['type' => 'complaint']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Target Selection -->
                <div style="margin-bottom: 20px;">
                    <label for="target_role" style="display: block; font-weight: 600; color: #193948; margin-bottom: 8px;">Recipient Role <span style="color: #ef4444;">*</span></label>
                    <select id="target_role" name="target_role" required style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; color: #193948;">
                        <option value="">Select Role</option>
                        @foreach($targets as $role)
                            <option value="{{ $role }}" {{ old('target_role') == $role ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $role)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="target_user_container" style="margin-bottom: 20px; display: none;">
                    <label for="target_user_id" style="display: block; font-weight: 600; color: #193948; margin-bottom: 8px;">Specific User (Optional)</label>
                    <input type="number" id="target_user_id" name="target_user_id" value="{{ old('target_user_id') }}" placeholder="User ID" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;">
                    <small style="color: #6b7280; display: block; margin-top: 4px;">Leave blank to send to all users with this role (if supported)</small>
                </div>

                <!-- Subject -->
                <div style="margin-bottom: 20px;">
                    <label for="subject" style="display: block; font-weight: 600; color: #193948; margin-bottom: 8px;">Subject <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="Brief summary of the issue" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;">
                </div>

                <!-- Message -->
                <div style="margin-bottom: 20px;">
                    <label for="message" style="display: block; font-weight: 600; color: #193948; margin-bottom: 8px;">Description <span style="color: #ef4444;">*</span></label>
                    <textarea id="message" name="message" rows="6" required placeholder="Detailed description of the issue..." style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; font-family: inherit;">{{ old('message') }}</textarea>
                </div>

                <!-- Location Link -->
                <div style="margin-bottom: 20px;">
                    <label for="location_link" style="display: block; font-weight: 600; color: #193948; margin-bottom: 8px;">Relevant Link (Optional)</label>
                    <input type="url" id="location_link" name="location_link" value="{{ old('location_link') }}" placeholder="https://..." style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;">
                </div>

                <!-- Images -->
                <div style="margin-bottom: 30px;">
                    <label for="images" style="display: block; font-weight: 600; color: #193948; margin-bottom: 8px;">Attachments (Images) - Max 5</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;">
                    <small style="color: #6b7280; display: block; margin-top: 4px;">Allowed formats: JPG, PNG. Max size: 10MB per file.</small>
                </div>

                <div style="display: flex; gap: 15px; justify-content: flex-end;">
                    <a href="{{ $type === 'report' ? route('superadmin.reports.index') : route('superadmin.complaints.index') }}" class="secondary-button" style="padding: 12px 24px; text-decoration: none;">
                        Cancel
                    </a>
                    <button type="submit" class="primary-button" style="padding: 12px 32px; font-size: 1rem;">
                        Submit {{ ucfirst($type) }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Optional: Dynamic user selection if we had an API endpoint to fetch users by role
            const roleSelect = document.getElementById('target_role');
            const userContainer = document.getElementById('target_user_container');
            
            roleSelect.addEventListener('change', function() {
                // Determine if we should show user ID input based on role
                // For now just show it always if role is selected, or customize logic
                if (this.value) {
                    userContainer.style.display = 'block';
                } else {
                    userContainer.style.display = 'none';
                }
            });
        });
    </script>
    @endpush
</x-allthepages-layout>

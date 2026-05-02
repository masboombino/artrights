<x-allthepages-layout pageTitle="{{ $type === 'report' ? 'Submit Report' : 'Submit Complaint' }}" :disableZoom="true">
    <div style="padding: 1rem;">
        @if($errors->any())
            <div class="stat-card" style="margin-bottom: 10px; padding: 1rem; background-color: #FEE2E2; border: 2px solid #E76268;">
                <ul style="margin: 0; padding-left: 1.5rem; color: #193948;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
            <h2 style="margin:0; color:#193948;">{{ $type === 'report' ? 'Submit Report' : 'Submit Complaint' }}</h2>
            <p style="margin:0.45rem 0 0; color:#36454f;">Gestionnaire submit flow with role-safe routing.</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('gestionnaire.complaints.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <!-- Target Selection -->
            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 1rem;">Send To: *</label>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @if($type === 'complaint')
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px; border: 2px solid #193948; border-radius:0.5rem; background-color: white;">
                            <input type="radio" name="target_role" value="super_admin" required>
                            <div>
                                <div style="font-weight: 600; color: #193948;">Super Admin</div>
                                <div style="font-size: 0.75rem; color: #36454f;">National Manager</div>
                            </div>
                        </label>
                    @endif
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px; border: 2px solid #193948; border-radius:0.5rem; background-color: white;">
                        <input type="radio" name="target_role" value="admin" required {{ $type === 'report' ? 'checked' : '' }}>
                        <div>
                            <div style="font-weight: 600; color: #193948;">Admin</div>
                            @if($admins && $admins->count() > 0)
                                <select name="target_user_id" style="margin-top: 5px; padding: 5px; border: 1px solid #193948; width: 100%; border-radius:0.4rem;">
                                    <option value="">Select Admin</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div style="font-size: 0.75rem; color: #36454f;">No admins available</div>
                            @endif
                        </div>
                    </label>
                </div>
            </div>

            <!-- Subject -->
            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label for="subject" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Subject *</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white; border-radius:0.5rem;"
                       placeholder="Enter {{ $type === 'report' ? 'report' : 'complaint' }} subject">
            </div>

            <!-- Message -->
            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label for="message" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Message *</label>
                <textarea id="message" name="message" rows="6" required
                          style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white; border-radius:0.5rem;"
                          placeholder="Describe your {{ $type === 'report' ? 'report' : 'complaint' }} in detail...">{{ old('message') }}</textarea>
            </div>

            <!-- Location Link -->
            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label for="location_link" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Location Link (Optional)</label>
                <input type="url" id="location_link" name="location_link" value="{{ old('location_link') }}"
                       style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white; border-radius:0.5rem;"
                       placeholder="https://maps.google.com/...">
            </div>

            <!-- Images -->
            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label for="images" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Images (Optional - Max 5 images, 10MB each)</label>
                <input type="file" id="images" name="images[]" multiple accept="image/*"
                       style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white; border-radius:0.5rem;">
                <p style="font-size: 0.75rem; color: #36454f; margin-top: 0.5rem;">You can upload up to 5 images to support your {{ $type === 'report' ? 'report' : 'complaint' }}.</p>
            </div>

            <!-- Files (Only for Reports) -->
            @if($type === 'report')
            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label for="files" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Files (Optional - PDF, DOC, DOCX, XLS, XLSX - Max 10MB each)</label>
                <input type="file" id="files" name="files[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx"
                       style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white; border-radius:0.5rem;">
                <p style="font-size: 0.75rem; color: #36454f; margin-top: 0.5rem;">You can upload documents (PDF, Word, Excel) to support your report.</p>
            </div>
            @endif

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
                <button type="submit" class="primary-button" style="background-color: {{ $type === 'report' ? '#10b981' : '#E76268' }}; color: white; padding: 0.75rem 1.5rem; border: none; cursor: pointer; font-weight: 600;">
                    {{ $type === 'report' ? '📊 Submit Report' : '⚠️ Submit Complaint' }}
                </button>
                <a href="{{ route('gestionnaire.reports-and-complaints.index') }}" class="secondary-button" style="padding: 0.75rem 1.5rem; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-allthepages-layout>

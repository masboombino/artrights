<x-allthepages-layout pageTitle="{{ $type === 'report' ? 'Submit Report' : 'Submit Complaint' }}" :disableZoom="true">
    <div style="padding: 5px; margin: 5px;">
        @if($errors->any())
            <div class="stat-card" style="margin-bottom: 10px; padding: 1rem; background-color: #FEE2E2; border: 2px solid #E76268;">
                <ul style="margin: 0; padding-left: 1.5rem; color: #193948;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Header -->
        <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;">
            <h2 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                {{ $type === 'report' ? '📊 Submit Report' : '⚠️ Submit Complaint' }}
            </h2>
            <p style="color: #36454f; font-size: 0.9rem;">
                @if($type === 'report')
                    Reports can only be sent to gestionnaires
                @else
                    Complaints can only be sent to admins
                @endif
            </p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('agent.complaints.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <!-- Target Selection -->
            <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;">
                <label style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 1rem;">Send To: *</label>
                @if($type === 'report')
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px; border: 2px solid #193948; background-color: white;">
                        <input type="radio" name="target_role" value="gestionnaire" required checked>
                        <div>
                            <div style="font-weight: 600; color: #193948;">Gestionnaire</div>
                            @if($gestionnaires->count() > 0)
                                <div style="font-size: 0.75rem; color: #36454f;">{{ $gestionnaires->count() }} gestionnaire(s) available</div>
                            @endif
                        </div>
                    </label>
                @else
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px; border: 2px solid #193948; background-color: white;">
                        <input type="radio" name="target_role" value="admin" required checked>
                        <div>
                            <div style="font-weight: 600; color: #193948;">Admin</div>
                            @if($admin)
                                <div style="font-size: 0.75rem; color: #36454f;">{{ $admin->name }}</div>
                            @endif
                        </div>
                    </label>
                @endif
            </div>

            <!-- Subject -->
            <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;">
                <label for="subject" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Subject *</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white;"
                       placeholder="Enter {{ $type === 'report' ? 'report' : 'complaint' }} subject">
            </div>

            <!-- Message -->
            <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;">
                <label for="message" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Message *</label>
                <textarea id="message" name="message" rows="6" required
                          style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white;"
                          placeholder="Describe your {{ $type === 'report' ? 'report' : 'complaint' }} in detail...">{{ old('message') }}</textarea>
            </div>

            <!-- Location Link -->
            <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;">
                <label for="location_link" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Location Link (Optional)</label>
                <input type="url" id="location_link" name="location_link" value="{{ old('location_link') }}"
                       style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white;"
                       placeholder="https://maps.google.com/...">
            </div>

            <!-- Images -->
            <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;">
                <label for="images" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Images (Optional - Max 5 images, 10MB each)</label>
                <input type="file" id="images" name="images[]" multiple accept="image/*"
                       style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white;">
                <p style="font-size: 0.75rem; color: #36454f; margin-top: 0.5rem;">You can upload up to 5 images to support your {{ $type === 'report' ? 'report' : 'complaint' }}.</p>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
                <button type="submit" class="primary-button" style="background-color: {{ $type === 'report' ? '#10b981' : '#E76268' }}; color: white; padding: 0.75rem 1.5rem; border: none; cursor: pointer; font-weight: 600;">
                    {{ $type === 'report' ? '📊 Submit Report' : '⚠️ Submit Complaint' }}
                </button>
                <a href="{{ route('agent.complaints.index') }}" class="secondary-button" style="padding: 0.75rem 1.5rem; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-allthepages-layout>

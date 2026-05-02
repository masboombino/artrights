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
            <h2 style="color:#193948; margin:0;">{{ $type === 'report' ? 'Submit Report' : 'Submit Complaint' }}</h2>
            <p style="margin:0.5rem 0 0; color:#36454f;">
                {{ $type === 'report' ? 'Reports are routed to Gestionnaire only.' : 'Complaints are routed to Admin only.' }}
            </p>
        </div>

        <form method="POST" action="{{ route('agent.complaints.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label style="display:block; color:#193948; font-weight:700; margin-bottom:0.7rem;">Send To</label>
                @if($type === 'report')
                    <label style="display:flex; align-items:center; gap:8px; padding:0.75rem; border:2px solid #193948; border-radius:0.5rem; background:white;">
                        <input type="radio" name="target_role" value="gestionnaire" required checked>
                        <div>
                            <div style="font-weight:700; color:#193948;">Gestionnaire</div>
                            @if($gestionnaires->count() > 0)
                                <div style="font-size:0.8rem; color:#36454f;">{{ $gestionnaires->count() }} available</div>
                            @endif
                        </div>
                    </label>
                @else
                    <label style="display:flex; align-items:center; gap:8px; padding:0.75rem; border:2px solid #193948; border-radius:0.5rem; background:white;">
                        <input type="radio" name="target_role" value="admin" required checked>
                        <div>
                            <div style="font-weight:700; color:#193948;">Admin</div>
                            @if($admin)
                                <div style="font-size:0.8rem; color:#36454f;">{{ $admin->name }}</div>
                            @endif
                        </div>
                    </label>
                @endif
            </div>

            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label for="subject" style="display:block; color:#193948; font-weight:700; margin-bottom:0.5rem;">Subject *</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                       style="width:100%; padding:0.75rem; border:2px solid #193948; border-radius:0.5rem; color:#193948; background:white;"
                       placeholder="Enter {{ $type === 'report' ? 'report' : 'complaint' }} subject">
            </div>

            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label for="message" style="display:block; color:#193948; font-weight:700; margin-bottom:0.5rem;">Message *</label>
                <textarea id="message" name="message" rows="6" required
                          style="width:100%; padding:0.75rem; border:2px solid #193948; border-radius:0.5rem; color:#193948; background:white;"
                          placeholder="Describe your {{ $type === 'report' ? 'report' : 'complaint' }} in detail...">{{ old('message') }}</textarea>
            </div>

            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label for="location_link" style="display:block; color:#193948; font-weight:700; margin-bottom:0.5rem;">Location Link (Optional)</label>
                <input type="url" id="location_link" name="location_link" value="{{ old('location_link') }}"
                       style="width:100%; padding:0.75rem; border:2px solid #193948; border-radius:0.5rem; color:#193948; background:white;"
                       placeholder="https://maps.google.com/...">
            </div>

            <div class="stat-card" style="padding: 1rem; margin-bottom: 1rem;">
                <label for="images" style="display:block; color:#193948; font-weight:700; margin-bottom:0.5rem;">Attachments (Optional, max 5 images)</label>
                <input type="file" id="images" name="images[]" multiple accept="image/*"
                       style="width:100%; padding:0.75rem; border:2px solid #193948; border-radius:0.5rem; color:#193948; background:white;">
                <p style="font-size:0.78rem; color:#36454f; margin-top:0.5rem;">Allowed: JPG, JPEG, PNG.</p>
            </div>

            <div style="display:flex; gap:0.6rem; flex-wrap:wrap;">
                <button type="submit" class="primary-button" style="background:{{ $type === 'report' ? '#10b981' : '#E76268' }}; color:white;">
                    {{ $type === 'report' ? 'Submit Report' : 'Submit Complaint' }}
                </button>
                <a href="{{ route('agent.complaints.index') }}" class="secondary-button" style="text-decoration:none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-allthepages-layout>

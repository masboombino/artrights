<x-allthepages-layout pageTitle="Submit Complaint" :disableZoom="true">
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
            <h2 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">⚠️ Submit Complaint</h2>
            <p style="color: #36454f; font-size: 0.9rem;">Choose the agency and recipient for your complaint</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('artist.complaints.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Agency Selection -->
            <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;">
                <label for="agency_id" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Select Agency *</label>
                <select name="agency_id" id="agency_id" required
                    style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white;">
                    <option value="">Choose an agency...</option>
                    @php
                        $artistAgency = Auth::user()->artist->agency;
                    @endphp
                    @if($artistAgency)
                        <option value="{{ $artistAgency->id }}" {{ old('agency_id') == $artistAgency->id ? 'selected' : '' }}>
                            {{ $artistAgency->agency_name }} - {{ $artistAgency->wilaya }} (Your Agency)
                        </option>
                        <optgroup label="Other Agencies">
                    @endif
                    @foreach($agencies as $agency)
                        @if(!$artistAgency || $agency->id !== $artistAgency->id)
                            <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                                {{ $agency->agency_name }} - {{ $agency->wilaya }}
                            </option>
                        @endif
                    @endforeach
                    @if($artistAgency)
                        </optgroup>
                    @endif
                </select>
                <p style="color: #36454f; font-size: 0.85rem; margin-top: 0.25rem;">Choose the agency where the issue occurred. You can send to admins only in your agency, but to gestionnaires in any agency.</p>
            </div>

            <!-- Target Selection -->
            <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;" id="target-selection" style="display: none;">
                <label style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 1rem;">Send To: *</label>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;" id="target-options">
                    <!-- Options will be loaded via AJAX -->
                </div>
            </div>

            <!-- Subject -->
            <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;">
                <label for="subject" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Subject *</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white;"
                       placeholder="Enter complaint subject">
            </div>

            <!-- Message -->
            <div class="stat-card" style="padding: 1.5rem; margin-bottom: 10px;">
                <label for="message" style="display: block; color: #193948; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">Message *</label>
                <textarea id="message" name="message" rows="6" required
                          style="width: 100%; padding: 0.75rem; border: 2px solid #193948; color: #193948; background-color: white;"
                          placeholder="Describe your complaint in detail...">{{ old('message') }}</textarea>
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
                <p style="font-size: 0.75rem; color: #36454f; margin-top: 0.5rem;">You can upload up to 5 images to support your complaint.</p>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
                <button type="submit" class="primary-button" style="background-color: #E76268; color: white; padding: 0.75rem 1.5rem; border: none; cursor: pointer; font-weight: 600;">
                    ⚠️ Submit Complaint
                </button>
                <a href="{{ route('artist.complaints.index') }}" class="secondary-button" style="padding: 0.75rem 1.5rem; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('agency_id').addEventListener('change', function() {
            const agencyId = this.value;
            const targetSelection = document.getElementById('target-selection');
            const targetOptions = document.getElementById('target-options');

            if (agencyId) {
                // Show loading
                targetOptions.innerHTML = '<div style="width: 100%; text-align: center; padding: 2rem; color: #36454f;">Loading...</div>';
                targetSelection.style.display = 'block';

                    // Fetch agency officials
                fetch(`/artist/api/agency/${agencyId}/officials`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';

                        // Admin option - only show for artist's own agency
                        if (data.admin) {
                            html += `
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px; border: 2px solid #193948; background-color: white; flex: 1; min-width: 200px;">
                                    <input type="radio" name="target_role" value="admin" required>
                                    <input type="hidden" name="target_user_id" value="${data.admin.id}">
                                    <div>
                                        <div style="font-weight: 600; color: #193948;">Admin</div>
                                        <div style="font-size: 0.75rem; color: #36454f;">${data.admin.name}</div>
                                    </div>
                                </label>
                            `;
                        }

                        // Gestionnaire option
                        if (data.gestionnaires && data.gestionnaires.length > 0) {
                            html += `
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px; border: 2px solid #193948; background-color: white; flex: 1; min-width: 200px;">
                                    <input type="radio" name="target_role" value="gestionnaire" required>
                                    <input type="hidden" name="target_user_id" value="${data.gestionnaires[0].id}">
                                    <div>
                                        <div style="font-weight: 600; color: #193948;">Gestionnaire</div>
                                        <div style="font-size: 0.75rem; color: #36454f;">${data.gestionnaires.length} gestionnaire(s)</div>
                                    </div>
                                </label>
                            `;
                        }

                        if (!html) {
                            html = '<div style="width: 100%; text-align: center; padding: 2rem; color: #E76268;">No officials available for this agency</div>';
                        }

                        targetOptions.innerHTML = html;

                        // Set default selection
                        const firstRadio = targetOptions.querySelector('input[type="radio"]');
                        if (firstRadio) {
                            firstRadio.checked = true;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        targetOptions.innerHTML = '<div style="width: 100%; text-align: center; padding: 2rem; color: #E76268;">Error loading officials</div>';
                    });
            } else {
                targetSelection.style.display = 'none';
                targetOptions.innerHTML = '';
            }
        });

        // Trigger change on page load if agency is pre-selected
        document.addEventListener('DOMContentLoaded', function() {
            const agencySelect = document.getElementById('agency_id');
            if (agencySelect.value) {
                agencySelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-allthepages-layout>

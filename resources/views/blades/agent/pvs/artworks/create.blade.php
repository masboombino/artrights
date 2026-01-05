<x-allthepages-layout pageTitle="Add Artwork Usage">
    <div class="space-y-6">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-4" style="color: #193948;">Add Artwork to PV</h2>
            
            <form method="POST" action="{{ route('agent.pvs.artworks.store', $pv) }}" class="space-y-4" id="artworkForm">
                @csrf

                <!-- Agency Selection -->
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Agency *</label>
                    <select name="agency_id" id="agency_id" class="w-full rounded border p-2" required>
                        <option value="">Select Agency</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->id }}" @selected(old('agency_id', $pv->agency_id) == $agency->id)>
                                {{ $agency->agency_name }} - {{ $agency->wilaya }}
                            </option>
                        @endforeach
                    </select>
                    @error('agency_id')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Artist Selection -->
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Artist *</label>
                    <select name="artist_id" id="artist_id" class="w-full rounded border p-2" required disabled>
                        <option value="">Select Agency first</option>
                    </select>
                    @error('artist_id')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Artwork Selection -->
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Artwork *</label>
                    <select name="artwork_id" id="artwork_id" class="w-full rounded border p-2" required disabled>
                        <option value="">Select Artist first</option>
                    </select>
                    @error('artwork_id')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Device Selection -->
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Device (optional)</label>
                    <select name="device_id" id="device_id" class="w-full rounded border p-2">
                        <option value="">Select device</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->id }}" @selected(old('device_id') == $device->id)>
                                {{ $device->name }} (Coefficient: {{ $device->coefficient }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Calculation Method -->
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #193948;">Calculation Method *</label>
                    <div class="flex gap-4 mb-4">
                        <label class="flex items-center">
                            <input type="radio" name="calculation_method" value="hours" id="method_hours" checked class="mr-2">
                            <span style="color: #193948;">Hours (for audio/video)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="calculation_method" value="count" id="method_count" class="mr-2">
                            <span style="color: #193948;">Usage Count (for images/static content)</span>
                        </label>
                    </div>
                </div>

                <!-- Hours or Count Input -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div id="hours_field">
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Hours Used *</label>
                        <input type="number" step="0.5" min="0.5" name="hours_used" id="hours_used" value="{{ old('hours_used', 1) }}" class="w-full rounded border p-2">
                    </div>
                    <div id="count_field" style="display: none;">
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Usage Count *</label>
                        <input type="number" min="1" name="usage_count" id="usage_count" value="{{ old('usage_count', 1) }}" class="w-full rounded border p-2">
                        <p class="text-xs mt-1" style="color: #6b7280;">Number of times the artwork was used</p>
                    </div>
                </div>

                <!-- Fine Calculation Preview -->
                <div class="p-4 rounded" style="background-color: #dbeafe; border: 2px solid #3b82f6;">
                    <h4 class="font-semibold mb-3" style="color: #193948;">Fine Calculation Preview</h4>
                    
                    <div class="space-y-2 mb-3">
                        <p class="text-sm" style="color: #193948;">
                            <strong>Category Coefficient:</strong> (<span id="display-category-coefficient" class="font-semibold">-</span>)
                        </p>
                        <p class="text-sm" style="color: #193948;">
                            <strong>Device Coefficient:</strong> (<span id="display-device-coefficient" class="font-semibold">-</span>)
                        </p>
                        <p class="text-sm" style="color: #193948;">
                            <strong id="time-label">Hours:</strong> (<span id="display-time" class="font-semibold">-</span>)
                        </p>
                        <p class="text-sm" style="color: #193948;">
                            <strong>Base Rate:</strong> <span id="display-base-rate" class="font-semibold">{{ number_format($pv->base_rate ?? config('artrights.base_rate', 200), 2) }} DZD</span>
                        </p>
                    </div>
                    
                    <div class="border-t-2 pt-3 mt-3" style="border-color: #3b82f6;">
                        <p class="text-sm mb-2" style="color: #193948;"><strong>Mathematical Calculation:</strong></p>
                        <div class="p-3 rounded mb-3" style="background-color: #ffffff; border: 1px solid #3b82f6;">
                            <p class="text-base font-mono text-center" style="color: #193948;">
                                <span id="calculation-formula">-</span>
                            </p>
                        </div>
                        <p class="text-lg font-bold text-center" style="color: #193948;">
                            Estimated Fine: <span id="fine-preview" class="text-xl">0.00</span> DZD
                        </p>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Notes</label>
                    <textarea name="notes" rows="4" class="w-full rounded border p-2">{{ old('notes') }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('agent.pvs.show', $pv) }}" class="rounded border px-4 py-2 font-semibold" style="color: #193948; border-color: #193948;">Cancel</a>
                    <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #193948; color: #4FADC0;">Save Usage</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Constants
        const BASE_RATE = {{ $pv->base_rate ?? config('artrights.base_rate', 200) }};
        const ARTISTS_URL = '{{ route("agent.pvs.artworks.artists", $pv) }}';
        const ARTWORKS_URL = '{{ route("agent.pvs.artworks.list", $pv) }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        // Variables
        let categoryCoefficient = 1;
        let deviceCoefficient = 1;
        let hours = 1;
        let usageCount = 1;
        let calculationMethod = 'hours';

        // Helper Functions
        function getElement(id) {
            return document.getElementById(id);
        }

        function updateDisplay(id, value) {
            const el = getElement(id);
            if (el) el.textContent = value;
        }

        function calculateFine() {
            const timeValue = calculationMethod === 'hours' 
                ? Math.max(hours, 0.5) 
                : Math.max(usageCount, 1);
            
            return categoryCoefficient * deviceCoefficient * timeValue * BASE_RATE;
        }

        function updateFinePreview() {
            const timeValue = calculationMethod === 'hours' 
                ? Math.max(hours, 0.5) 
                : Math.max(usageCount, 1);
            
            const fine = calculateFine();
            
            // Update displays
            updateDisplay('display-category-coefficient', categoryCoefficient.toFixed(2));
            updateDisplay('display-device-coefficient', deviceCoefficient.toFixed(2));
            
            if (calculationMethod === 'hours') {
                updateDisplay('display-time', timeValue.toFixed(2));
            } else {
                updateDisplay('display-time', Math.floor(timeValue).toString());
            }
            
            // Update formula
            const timeStr = calculationMethod === 'hours' 
                ? timeValue.toFixed(2) 
                : Math.floor(timeValue).toString();
            
            const formula = `{(${categoryCoefficient.toFixed(2)}) × (${deviceCoefficient.toFixed(2)}) × (${timeStr}) × ${BASE_RATE.toFixed(2)}} = ${fine.toFixed(2)} DZD`;
            updateDisplay('calculation-formula', formula);
            updateDisplay('fine-preview', fine.toFixed(2));
        }

        function toggleCalculationMethod() {
            const isHours = getElement('method_hours').checked;
            calculationMethod = isHours ? 'hours' : 'count';
            
            const hoursField = getElement('hours_field');
            const countField = getElement('count_field');
            const hoursInput = getElement('hours_used');
            const countInput = getElement('usage_count');
            const timeLabel = getElement('time-label');
            
            if (isHours) {
                hoursField.style.display = 'block';
                countField.style.display = 'none';
                if (hoursInput) hoursInput.required = true;
                if (countInput) countInput.required = false;
                if (timeLabel) timeLabel.textContent = 'Hours:';
            } else {
                hoursField.style.display = 'none';
                countField.style.display = 'block';
                if (hoursInput) hoursInput.required = false;
                if (countInput) countInput.required = true;
                if (timeLabel) timeLabel.textContent = 'Usage Count:';
            }
            
            updateFinePreview();
        }

        function loadArtists(agencyId, preselectArtist = null, preselectArtwork = null) {
            if (!agencyId) {
                const artistSelect = getElement('artist_id');
                artistSelect.innerHTML = '<option value="">Select Agency first</option>';
                artistSelect.disabled = true;
                getElement('artwork_id').innerHTML = '<option value="">Select Artist first</option>';
                getElement('artwork_id').disabled = true;
                return Promise.resolve();
            }

            return fetch(ARTISTS_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ agency_id: agencyId })
            })
            .then(response => response.json())
            .then(data => {
                const artistSelect = getElement('artist_id');
                artistSelect.innerHTML = '<option value="">Select Artist</option>';
                data.forEach(artist => {
                    const option = document.createElement('option');
                    option.value = artist.id;
                    option.textContent = artist.name + (artist.stage_name ? ' (' + artist.stage_name + ')' : '');
                    artistSelect.appendChild(option);
                });
                artistSelect.disabled = false;
                
                const artworkSelect = getElement('artwork_id');
                artworkSelect.innerHTML = '<option value="">Select Artist first</option>';
                artworkSelect.disabled = true;
                updateFinePreview();

                if (preselectArtist && data.some(artist => artist.id == preselectArtist)) {
                    artistSelect.value = preselectArtist;
                    return loadArtworks(preselectArtist, preselectArtwork);
                }
            });
        }

        function loadArtworks(artistId, preselectArtwork = null) {
            if (!artistId) {
                getElement('artwork_id').innerHTML = '<option value="">Select Artist first</option>';
                getElement('artwork_id').disabled = true;
                return Promise.resolve();
            }

            return fetch(ARTWORKS_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ artist_id: artistId })
            })
            .then(response => response.json())
            .then(data => {
                const artworkSelect = getElement('artwork_id');
                artworkSelect.innerHTML = '<option value="">Select Artwork</option>';
                data.forEach(artwork => {
                    const option = document.createElement('option');
                    option.value = artwork.id;
                    option.textContent = artwork.title + ' (' + artwork.category + ')';
                    option.setAttribute('data-coefficient', artwork.category_coefficient);
                    artworkSelect.appendChild(option);
                });
                artworkSelect.disabled = false;
                updateFinePreview();

                if (preselectArtwork && data.some(artwork => artwork.id == preselectArtwork)) {
                    artworkSelect.value = preselectArtwork;
                    artworkSelect.dispatchEvent(new Event('change'));
                }
            });
        }

        // Event Listeners
        getElement('agency_id').addEventListener('change', function() {
            loadArtists(this.value);
        });

        getElement('artist_id').addEventListener('change', function() {
            loadArtworks(this.value);
        });

        getElement('artwork_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            categoryCoefficient = parseFloat(selectedOption.getAttribute('data-coefficient')) || 1;
            updateFinePreview();
        });

        getElement('device_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const match = selectedOption.text.match(/Coefficient: ([\d.]+)/);
                deviceCoefficient = match ? parseFloat(match[1]) : 1;
            } else {
                deviceCoefficient = 1;
            }
            updateFinePreview();
        });

        getElement('method_hours').addEventListener('change', toggleCalculationMethod);
        getElement('method_count').addEventListener('change', toggleCalculationMethod);

        getElement('hours_used').addEventListener('input', function() {
            hours = parseFloat(this.value) || 0.5;
            updateFinePreview();
        });

        getElement('usage_count').addEventListener('input', function() {
            usageCount = parseFloat(this.value) || 1;
            updateFinePreview();
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateFinePreview();
            const defaultAgency = @json(old('agency_id', $pv->agency_id));
            const defaultArtist = @json(old('artist_id'));
            const defaultArtwork = @json(old('artwork_id'));

            if (defaultAgency) {
                getElement('agency_id').value = defaultAgency;
                loadArtists(defaultAgency, defaultArtist, defaultArtwork);
            }
        });
    </script>
</x-allthepages-layout>

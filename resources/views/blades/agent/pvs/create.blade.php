<x-allthepages-layout pageTitle="Create PV">
    <div style="padding: 5px; margin: 5px;">
        @isset($mission)
            <div style="background-color: #F3EBDD; border-radius: 1rem; padding: 1.5rem; margin: 5px; ">
                <p style="color: #193948; font-size: 1rem; margin: 5px;">
                    Mission: <span style="font-weight: 700;">{{ $mission->title }}</span>
                </p>
                <p style="color: #193948; font-size: 0.9rem; margin: 5px;">
                    Location: {{ $mission->location_text ?? 'Not provided' }}
                </p>
            </div>
        @endisset

        <div style="background-color: #F3EBDD; border-radius: 1rem; padding: 2rem; margin: 5px; ">
            <form method="POST" action="{{ route('agent.pvs.store') }}" enctype="multipart/form-data">
                @csrf
                @isset($mission)
                    <input type="hidden" name="mission_id" value="{{ $mission->id }}">
                @endisset

                <div style="margin: 5px; padding: 5px;">
                    <label style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Shop Name
                    </label>
                    <input type="text" name="shop_name" value="{{ old('shop_name') }}" required
                           style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem;">
                    @error('shop_name')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Shop Type
                    </label>
                    <input type="text" name="shop_type" value="{{ old('shop_type') }}" required
                           style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem;">
                    @error('shop_type')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Inspection Date & Time
                    </label>
                    <input type="datetime-local" id="inspection_date" name="date_of_inspection" 
                           value="{{ old('date_of_inspection', now()->format('Y-m-d\TH:i')) }}" required
                           style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem;">
                    @error('date_of_inspection')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Photos (max 25 images)
                    </label>
                    <input type="file" name="report_files[]" accept="image/*" multiple
                           style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem;">
                    <p style="color: #193948; font-size: 0.85rem; margin-top: 0.5rem; opacity: 0.8;">
                        Upload JPEG or PNG evidence from the location.
                    </p>
                    @error('report_files')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Notes
                    </label>
                    <textarea name="notes" rows="4" 
                              style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem;">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 1.5rem; padding: 5px;">
                    <a href="{{ route('agent.pvs.index') }}" class="secondary-button">
                        Cancel
                    </a>
                    <button type="submit" class="primary-button">
                        Create PV
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('inspection_date');
            if (dateInput && !dateInput.value) {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                dateInput.value = now.toISOString().slice(0, 16);
            }
        });
    </script>
</x-allthepages-layout>

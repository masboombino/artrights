<x-allthepages-layout pageTitle="Submit {{ ucfirst($type ?? 'Complaint') }}" :disableZoom="true">
    <div style="padding: 5px; margin: 5px;">
        <div style="background-color: #F3EBDD; border-radius: 1rem; padding: 2rem; margin: 5px; border: 2px solid #193948;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 1.5rem;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background-color: {{ ($type ?? 'complaint') === 'report' ? '#10b981' : '#E76268' }}; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                    {{ ($type ?? 'complaint') === 'report' ? '📊' : '⚠️' }}
                </div>
                <div>
                    <h2 style="color: #D6BFBF; font-size: 1.75rem; font-weight: 700; margin: 0;">
                        Submit a {{ ucfirst($type ?? 'Complaint') }}
                    </h2>
                    <p style="color: #193948; font-size: 0.9rem; margin-top: 0.25rem; opacity: 0.8;">
                        {{ ($type ?? 'complaint') === 'report' ? 'Share information or updates with your team' : 'Report an issue or concern to your team' }}
                    </p>
                </div>
            </div>
            
            <form action="{{ route('admin.complaints.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type ?? 'complaint' }}">
                
                @if(!empty($targets))
                    <div style="margin: 5px; padding: 5px; margin-bottom: 1rem;">
                        <label for="target_role" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                            Send To *
                        </label>
                        <select name="target_role" id="target_role" required
                            style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white;">
                            @foreach($targets as $target)
                                <option value="{{ $target }}" @selected(old('target_role') === $target)>
                                    {{ ucfirst(str_replace('_', ' ', $target)) }}
                                </option>
                            @endforeach
                        </select>
                        <p style="color: #193948; font-size: 0.85rem; margin-top: 0.25rem; opacity: 0.8;">
                            Choose who should receive this {{ $type ?? 'complaint' }}.
                        </p>
                        @error('target_role')
                            <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div style="margin: 5px; padding: 5px; margin-bottom: 1rem;">
                    <label for="subject" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Subject *
                    </label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                        placeholder="Enter a clear subject line"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white;">
                    @error('subject')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px; margin-bottom: 1rem;">
                    <label for="message" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Message *
                    </label>
                    <textarea name="message" id="message" rows="10" required
                        placeholder="Describe your {{ $type ?? 'complaint' }} in detail..."
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white; resize: vertical;">{{ old('message') }}</textarea>
                    @error('message')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px; margin-bottom: 1rem;">
                    <label for="location_link" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Location Link (Optional)
                    </label>
                    <input type="url" name="location_link" id="location_link" value="{{ old('location_link') }}"
                        placeholder="https://maps.google.com/..."
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white;">
                    <p style="color: #193948; font-size: 0.85rem; margin-top: 0.25rem; opacity: 0.8;">
                        Add a Google Maps link or any location reference if relevant.
                    </p>
                    @error('location_link')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px; margin-bottom: 1rem;">
                    <label for="images" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Images (Optional, Max 5 images, 10MB each)
                    </label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white;">
                    <p style="color: #193948; font-size: 0.85rem; margin-top: 0.5rem; opacity: 0.8;">
                        📷 You can upload up to 5 images as evidence or documentation. Maximum size per image: 10MB
                    </p>
                    @error('images.*')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; gap: 10px; margin-top: 1.5rem; padding: 5px; flex-wrap: wrap;">
                    <button type="submit" class="primary-button" style="background-color: {{ ($type ?? 'complaint') === 'report' ? '#10b981' : '#E76268' }};">
                        Submit {{ ucfirst($type ?? 'Complaint') }}
                    </button>
                    <a href="{{ route('admin.messages.index') }}" class="secondary-button">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>


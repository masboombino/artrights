<x-allthepages-layout pageTitle="Create {{ ucfirst($type ?? 'Complaint') }}">
    <div style="padding: 5px; margin: 5px;">
        <div style="background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%); border-radius: 1rem; padding: 2rem; margin: 5px; border: 2px solid #193948;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 2rem;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background-color: {{ ($type ?? 'complaint') === 'report' ? '#10b981' : '#E76268' }}; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                    {{ ($type ?? 'complaint') === 'report' ? '📊' : '⚠️' }}
                </div>
                <div>
                    <h2 style="color: #193948; font-size: 1.75rem; font-weight: 700; margin: 0;">
                        Create New {{ ucfirst($type ?? 'Complaint') }}
                    </h2>
                    <p style="color: #193948; font-size: 0.9rem; margin-top: 0.25rem; opacity: 0.8;">
                        {{ ($type ?? 'complaint') === 'report' ? 'Share information or updates' : 'Report an issue or concern' }}
                    </p>
                </div>
            </div>
            
            <form action="{{ route('artist.messages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type ?? 'complaint' }}">
                
                @if(!empty($targets))
                    <div style="margin-bottom: 1.5rem;">
                        <label for="target_role" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                            📤 Send To *
                        </label>
                        <select name="target_role" id="target_role" required
                            style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white;">
                            @foreach($targets as $target)
                                <option value="{{ $target }}" @selected(old('target_role', 'gestionnaire') === $target)>
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

                <div style="margin-bottom: 1.5rem;">
                    <label for="subject" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        📝 Subject *
                    </label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                        placeholder="Enter a clear subject line"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white;">
                    @error('subject')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="message" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        💬 Message *
                    </label>
                    <textarea name="message" id="message" rows="10" required
                        placeholder="Describe your {{ $type ?? 'complaint' }} in detail..."
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white; resize: vertical;">{{ old('message') }}</textarea>
                    @error('message')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="location_link" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        📍 Location Link (Optional)
                    </label>
                    <input type="url" name="location_link" id="location_link" value="{{ old('location_link') }}"
                        placeholder="https://maps.google.com/..."
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white;">
                    @error('location_link')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="images" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        📷 Images (Optional, Max 5 images, 10MB each)
                    </label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: white;">
                    @error('images.*')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; gap: 10px; margin-top: 2rem; flex-wrap: wrap;">
                    <button type="submit" style="padding: 12px 32px; background-color: {{ ($type ?? 'complaint') === 'report' ? '#10b981' : '#E76268' }}; color: white; border-radius: 0.5rem; border: none; font-weight: 600; font-size: 1rem; cursor: pointer;">
                        ✉️ Send {{ ucfirst($type ?? 'Complaint') }}
                    </button>
                    <a href="{{ route('artist.messages.index') }}" style="padding: 12px 32px; background-color: #D6BFBF; color: #193948; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 1rem;">
                        ❌ Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>







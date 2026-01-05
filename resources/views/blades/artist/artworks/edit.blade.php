<x-allthepages-layout pageTitle="Edit Artwork">
    <div style="padding: 5px; margin: 5px;">
        <div style="background-color: #F3EBDD; border-radius: 1rem; padding: 2rem; margin: 5px; ">
            <h2 style="color: #193948; font-size: 1.75rem; font-weight: 700; margin-bottom: 1.5rem;">Edit Artwork</h2>
            
            <form action="{{ route('artist.update-artwork', $artwork->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div style="margin: 5px; padding: 5px;">
                    <label for="title" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Title *
                    </label>
                    <input type="text" name="title" id="title" value="{{ $artwork->title }}" required
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem;">
                    @error('title')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Category
                    </label>
                    <div style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem; background-color: #f0f0f0;">
                        {{ $artwork->category->name ?? 'N/A' }}
                    </div>
                    <p style="color: #36454f; font-size: 0.85rem; margin-top: 0.5rem; opacity: 0.8;">
                        Category cannot be changed after creation.
                    </p>
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label for="description" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem;">{{ $artwork->description }}</textarea>
                    @error('description')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin: 5px; padding: 5px;">
                    <label for="file" style="display: block; color: #193948; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        File (Image, PDF, Audio, Video)
                    </label>
                    @if($artwork->file_path)
                        <p style="color: #193948; font-size: 0.9rem; margin-bottom: 0.5rem;">
                            Current file: {{ basename($artwork->file_path) }}
                        </p>
                    @endif
                    <input type="file" name="file" id="file"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem;">
                    <p style="color: #193948; font-size: 0.85rem; margin-top: 0.5rem; opacity: 0.8;">
                        Accepted formats: JPG, PNG, PDF, MP3, MP4 (Max: 10MB). Leave blank to keep current file.
                    </p>
                    @error('file')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; gap: 10px; margin-top: 1.5rem; padding: 5px; flex-wrap: wrap;">
                    <button type="submit" class="primary-button">
                        Update Artwork
                    </button>
                    <a href="{{ route('artist.artworks') }}" class="secondary-button">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>

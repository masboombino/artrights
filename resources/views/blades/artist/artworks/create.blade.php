<x-allthepages-layout pageTitle="Create Artwork">
    <style>
        .create-artwork-container {
            padding: 5px;
            margin: 5px;
        }

        .create-artwork-form {
            background-color: #F3EBDD;
            border-radius: 1rem;
            padding: 2rem;
            margin: 5px;
        }

        .form-group {
            margin: 5px;
            padding: 5px;
        }

        .form-input-custom {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #193948;
            border-radius: 0.5rem;
            color: #193948;
            font-size: 1rem;
        }

        .form-label-custom {
            display: block;
            color: #193948;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .error-message {
            color: #E76268;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .create-artwork-form {
                padding: 1.5rem 1rem;
                margin: 3px;
            }

            .create-artwork-container {
                padding: 3px;
                margin: 3px;
            }

            .form-group {
                margin: 3px;
                padding: 3px;
            }

            .form-input-custom {
                padding: 0.6rem;
                font-size: 0.95rem;
            }

            .form-label-custom {
                font-size: 0.9rem;
                margin-bottom: 0.4rem;
            }
        }

        @media (max-width: 640px) {
            .create-artwork-form {
                padding: 1rem 0.75rem;
                margin: 2px;
            }

            .create-artwork-container {
                padding: 2px;
                margin: 2px;
            }

            .form-group {
                margin: 2px;
                padding: 2px;
            }

            .form-input-custom {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            .form-label-custom {
                font-size: 0.85rem;
                margin-bottom: 0.3rem;
            }

            .error-message {
                font-size: 0.8rem;
            }
        }
    </style>

    <div class="create-artwork-container">
        <div class="create-artwork-form">
            <h2 style="color: #193948; font-size: 1.75rem; font-weight: 700; margin-bottom: 1.5rem;">Create New Artwork</h2>
            
            <form action="{{ route('artist.store-artwork') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="title" class="form-label-custom">
                        Title *
                    </label>
                    <input type="text" name="title" id="title" required class="form-input-custom">
                    @error('title')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id" class="form-label-custom">
                        Category *
                    </label>
                    <select name="category_id" id="category_id" required class="form-input-custom">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label-custom">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4" class="form-input-custom"></textarea>
                    @error('description')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="file" class="form-label-custom">
                        File (Image, PDF, Audio, Video)
                    </label>
                    <input type="file" name="file" id="file"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #193948; border-radius: 0.5rem; color: #193948; font-size: 1rem;">
                    <p style="color: #193948; font-size: 0.85rem; margin-top: 0.5rem; opacity: 0.8;">
                        Accepted formats: JPG, PNG, PDF, MP3, MP4 (Max: 10MB)
                    </p>
                    @error('file')
                        <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; gap: 10px; margin-top: 1.5rem; padding: 5px; flex-wrap: wrap;">
                    <button type="submit" class="primary-button">
                        Create Artwork
                    </button>
                    <a href="{{ route('artist.artworks') }}" class="secondary-button">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>

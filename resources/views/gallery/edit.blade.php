@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-2xl font-bold mb-6">Edit Image</h1>
    
    <form action="{{ route('gallery.update', $image->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        {{-- Image Preview & Upload --}}
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Replace Image (Optional)</label>
            <input type="file" id="image" name="image" class="mt-2 block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500" onchange="previewImage(event)">
            <img id="preview" src="{{ asset('storage/images/gallery/' . $image->album->category->name . '/' . $image->album->name . '/' . 'large_' . $image->image) }}" alt="{{ $image->title }}" class="mt-4 w-full max-h-96 rounded-lg shadow-lg object-cover">
        </div>

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $image->title) }}" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        {{-- Date Taken --}}
        <div>
            <label for="date_taken" class="block text-sm font-medium text-gray-700">Date Taken</label>
            <input type="date" id="date_taken" name="date_taken" value="{{ old('date_taken', $image->date_taken) }}" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Location --}}
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
            <input type="text" id="location" name="location" value="{{ old('location', $image->location) }}" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        {{-- Summary --}}
        <div>
            <label for="summary" class="block text-sm font-medium text-gray-700">Summary</label>
            <textarea id="summary" name="summary" rows="3" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('summary', $image->summary) }}</textarea>
        </div>

        {{-- Text --}}
        <div>
            <label for="text" class="block text-sm font-medium text-gray-700">Text</label>
            <textarea id="text" name="text" rows="5" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('text', $image->text) }}</textarea>
        </div>

    {{-- Category --}}
    <div>
        <label for="category_name" class="block text-sm font-medium text-gray-700">Category</label>
        <select id="category_name" name="category_name" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            <option value="" disabled>Select a Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->name }}" {{ $image->album->category->name === $category->name ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Album --}}
    <div>
        <label for="album_name" class="block text-sm font-medium text-gray-700">Album</label>
        <select id="album_name" name="album_name" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            <option value="" disabled>Select an Album</option>
            @foreach($image->album->category->albums as $album)
                <option value="{{ $album->name }}" {{ $image->album->name === $album->name ? 'selected' : '' }}>
                    {{ $album->name }}
                </option>
            @endforeach
        </select>
    </div>

        {{-- Tags --}}
        <div>
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
            <input type="text" id="tags" name="tags" 
                value="{{ old('tags', $image->tags->pluck('name')->implode(', ')) }}" 
                class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Add tags, separated by commas (e.g., historical, Auschwitz)">
        </div>

        {{-- Featured & Published --}}
        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <input type="checkbox" id="featured" name="featured" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $image->featured ? 'checked' : '' }}>
                <label for="featured" class="ml-2 block text-sm text-gray-700">Featured</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="published" name="published" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $image->published ? 'checked' : '' }}>
                <label for="published" class="ml-2 block text-sm text-gray-700">Published</label>
            </div>
        </div>

        {{-- Submit Button --}}
        <div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Update Image
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get references to category and album selects
        const categorySelect = document.getElementById('category_name');
        const albumSelect = document.getElementById('album_name');

        // Parse categories and albums data from the Blade template
        const categories = @json($categories);

        categorySelect.addEventListener('change', function () {
            const selectedCategoryName = this.value;
            const currentAlbumName = "{{ $image->album->name }}";

            // Clear previous albums
            albumSelect.innerHTML = '<option value="" disabled selected>Select an Album</option>';

            // Find the selected category in the categories array
            const selectedCategory = categories.find(category => category.name === selectedCategoryName);

            if (selectedCategory && selectedCategory.albums.length > 0) {
                selectedCategory.albums.forEach(album => {
                    const option = document.createElement('option');
                    option.value = album.name;
                    option.textContent = album.name;

                    // Preserve current album selection
                    if (album.name === currentAlbumName) {
                        option.selected = true;
                    }

                    albumSelect.appendChild(option);
                });
            } else {
                // Display message if no albums are available
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "No albums available for this category";
                option.disabled = true;
                albumSelect.appendChild(option);
            }
        });
    });

    // Preview image before uploading
    function previewImage(event) {
        const preview = document.getElementById('preview');
        preview.src = URL.createObjectURL(event.target.files[0]);
    }
</script>
@endsection
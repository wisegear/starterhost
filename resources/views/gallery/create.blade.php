@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-2xl font-bold mb-6">Upload a New Image</h1>
    
    <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        {{-- Image Upload --}}
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Upload Image</label>
            <input type="file" id="image" name="image" class="mt-2 block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500" required onchange="previewImage(event)">
            <img id="preview" src="#" alt="Image Preview" class="hidden mt-4 w-full max-h-64 rounded-lg shadow-lg object-cover">
        </div>

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter a title for the image" required>
        </div>

        {{-- Date Taken --}}
        <div>
            <label for="date_taken" class="block text-sm font-medium text-gray-700">Date Taken</label>
            <input type="date" id="date_taken" name="date_taken" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Location --}}
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
            <input type="text" id="location" name="location" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter the location">
        </div>

        {{-- Summary --}}
        <div>
            <label for="summary" class="block text-sm font-medium text-gray-700">Summary</label>
            <textarea id="summary" name="summary" rows="3" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Write a short summary"></textarea>
        </div>

        {{-- Text --}}
        <div>
            <label for="text" class="block text-sm font-medium text-gray-700">Text</label>
            <textarea id="text" name="text" rows="5" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Add additional text or details"></textarea>
        </div>

        {{-- Category --}}
        <div>
            <label for="category_name" class="block text-sm font-medium text-gray-700">Category</label>
            <select id="category_name" name="category_name" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="" disabled selected>Select a Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Album --}}
        <div>
            <label for="album_name" class="block text-sm font-medium text-gray-700">Album</label>
            <select id="album_name" name="album_name" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="" disabled selected>Select an Album</option>
            </select>
        </div>

        {{-- Tags --}}
        <div>
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
            <input type="text" id="tags" name="tags" class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Add tags, separated by commas (e.g., historical, Auschwitz)">
        </div>

        {{-- Featured & Published --}}
        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <input type="checkbox" id="featured" name="featured" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="featured" class="ml-2 block text-sm text-gray-700">Featured</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="published" name="published" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                <label for="published" class="ml-2 block text-sm text-gray-700">Published</label>
            </div>
        </div>

        {{-- Submit Button --}}
        <div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Upload Image
            </button>
        </div>
    </form>
</div>

<script>
    // Image preview
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0]; // Get the selected file

        if (file) {
            const reader = new FileReader(); // FileReader to handle the file

            reader.onload = function (e) {
                preview.src = e.target.result; // Set the image preview source
                preview.classList.remove('hidden'); // Display the image preview
            };

            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            preview.src = "#";
            preview.classList.add('hidden'); // Hide the preview if no file is selected
        }
    }

    // Dynamic album dropdown based on category selection
    const categories = @json($categories);
    const categorySelect = document.getElementById('category_name');
    const albumSelect = document.getElementById('album_name');

    categorySelect.addEventListener('change', (event) => {
        const selectedCategoryName = event.target.value;

        albumSelect.innerHTML = '<option value="" disabled selected>Select an Album</option>';

        const category = categories.find(cat => cat.name === selectedCategoryName);
        if (category && category.albums.length > 0) {
            category.albums.forEach(album => {
                const option = document.createElement('option');
                option.value = album.name;
                option.textContent = album.name;
                albumSelect.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.value = "";
            option.textContent = "No albums available for this category";
            option.disabled = true;
            albumSelect.appendChild(option);
        }
    });
</script>
@endsection
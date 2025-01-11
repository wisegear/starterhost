@extends('layouts.app')

@section('content')
<h2 class="text-4xl font-bold text-center mb-8">Create a New Post</h2>

<div class="w-full mx-auto bg-white shadow-md rounded-lg p-6">
    <!-- Form Start -->
    <form method="POST" action="/blog" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Upload Featured Image -->
        <div class="space-y-2">
            <label for="image" class="block text-sm font-medium text-gray-700">Upload Featured Image</label>
            <input type="file" name="image" id="image" accept="image/*" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" onchange="previewImage(event)">
            <div id="image-preview" class="mt-4">
                <img id="preview" class="w-full hidden" alt="Image Preview" />
            </div>
        </div>

        <!-- Post Date -->
        <div class="space-y-2">
            <label for="date" class="block text-sm font-medium text-gray-700">Post Date</label>
            <input type="date" id="date" name="date" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('date') }}">
            @error('date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Post Title -->
        <div class="space-y-2">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter a title for this post" value="{{ old('title') }}">
            @error('title')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Summary (Excerpt) -->
        <div class="space-y-2">
            <label for="summary" class="block text-sm font-medium text-gray-700">Summary</label>
            <textarea id="summary" name="summary" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" rows="3" placeholder="Enter a brief summary">{{ old('summary') }}</textarea>
            @error('summary')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Body (with TinyMCE) -->
        <div class="space-y-2">
            <label for="body" class="block text-sm font-medium text-gray-700">Post Body</label>
            <textarea id="editor" name="body" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" rows="10">{{ old('body') }}</textarea>
            @error('body')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-between">
            <!-- Additional Images Upload -->
            <div class="space-y-2">
                <label for="images" class="block text-sm font-medium text-gray-700">Additional Images</label>
                <input type="file" name="images[]" id="images" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" multiple>
            </div>
            <!-- New Gallery Images Upload Section -->
            <div class="space-y-2">
                <label for="gallery_images" class="block text-sm font-medium text-gray-700">Gallery Images</label>
                <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" multiple onchange="previewGalleryImages(event)">
                <small class="text-gray-500">You can upload multiple images for the gallery. These will be displayed in a gallery format on the post.</small>
                <div id="gallery-preview" class="mt-4 flex flex-wrap gap-4"></div>
            </div> 
        </div>

        <!-- Category Selection -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <div class="space-y-2">
                @foreach($categories as $category)
                    <label class="inline-flex items-center">
                        <input type="radio" name="category" value="{{ $category->id }}" class="text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('category')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Tags -->
        <div class="space-y-2">
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
            <input type="text" id="tags" name="tags" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter tags separated by commas">
        </div>

        <!-- Post Options -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Post Options</label>
            <div class="flex items-center space-x-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="published" name="published" class="text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2">Publish</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" id="featured" name="featured" class="text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2">Featured</span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="bg-lime-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-lime-500 focus:outline-none focus:ring-2 focus:ring-lime-500">
                Create New Post
            </button>
        </div>
    </form>
</div>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/a1rn9rzvnlulpzdgoe14w7kqi1qpfsx7cx9am2kbgg226dqz/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'advlist autolink lists link image charmap preview anchor code fullscreen insertdatetime media table paste help wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        branding: false,
    });

    // Preview the selected gallery images
    function previewGalleryImages(event) {
        const galleryPreview = document.getElementById('gallery-preview');
        galleryPreview.innerHTML = ''; // Clear existing previews

        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function () {
                const img = document.createElement('img');
                img.src = reader.result;
                img.classList.add('w-32', 'h-32', 'object-cover', 'rounded-lg', 'shadow-md');
                galleryPreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }

    // Preview the featured image
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('preview');
            output.src = reader.result;
            output.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
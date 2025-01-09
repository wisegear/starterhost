@extends('layouts.app')

@section('content')

<h2 class="text-4xl font-bold text-center mb-8">Edit Post</h2>

<div class="w-4/5 mx-auto bg-white shadow-md rounded-lg p-6">

    <!-- Form Start -->
    <form method="POST" action="/blog/{{ $page->id }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Upload Featured Image -->
        <div class="space-y-2">
            <label for="image" class="block text-sm font-medium text-gray-700">Upload New Featured Image (optional)</label>
            <input type="file" name="image" id="image" accept="image/*" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" onchange="previewImage(event)">
            <div id="image-preview" class="mt-4">
                <img id="preview" class="w-full hidden" alt="Image Preview" />
            </div>
        </div>

        <!-- Existing Featured Image -->
        <div class="space-y-2">
            <h3 class="text-lg font-medium text-gray-700">Current Featured Image</h3>
            @if($page->small_image)
                <img src="{{ asset($page->small_image) }}" class="w-full rounded-lg border" alt="Featured Image">
            @else
                <p class="text-gray-600">No featured image available</p>
            @endif
        </div>

        <!-- Date Field -->
        <div class="space-y-2">
            <label for="date" class="block text-sm font-medium text-gray-700">Post Date</label>
            <input type="date" id="date" name="date" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('date', $page->getRawOriginal('date')) }}">
            @error('date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Post Title -->
        <div class="space-y-2">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter a title for this post" value="{{ old('title', $page->title) }}">
            @error('title')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Summary (Excerpt) -->
        <div class="space-y-2">
            <label for="summary" class="block text-sm font-medium text-gray-700">Summary</label>
            <textarea id="summary" name="summary" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" rows="3">{{ old('summary', $page->summary) }}</textarea>
            @error('summary')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Body (with TinyMCE) -->
        <div class="space-y-2">
            <label for="body" class="block text-sm font-medium text-gray-700">Post Body</label>
            <textarea id="editor" name="body" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" rows="10">{{ old('body', $page->body) }}</textarea>
            @error('body')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Additional Images Upload -->
        <div class="space-y-2">
            <label for="images" class="block text-sm font-medium text-gray-700">Additional Images</label>
            <input type="file" name="images[]" id="editorImages" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" multiple>
        </div>

        <!-- Uploaded Images Preview -->
        <div class="space-y-4">
            <h4 class="text-lg font-medium text-gray-700">Uploaded Images</h4>
            @if($page->images)
                <div class="grid grid-cols-4 gap-4">
                    @foreach(json_decode($page->images) as $image)
                        <div class="relative">
                            <img src="{{ asset($image) }}" class="h-24 w-full rounded-lg border" alt="Uploaded Image">
                            <button onclick="copyToClipboard('{{ asset($image) }}')" class="absolute top-1 right-1 bg-gray-700 text-white text-xs px-2 py-1 rounded">Copy URL</button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">No additional images uploaded yet.</p>
            @endif
        </div>

        <!-- Category Selection -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <div class="space-y-2">
                @foreach($categories as $category)
                    <label class="inline-flex items-center">
                        <input type="radio" name="category" value="{{ $category->id }}" class="text-indigo-600 focus:ring-indigo-500" @if ($page->categories_id === $category->id) checked @endif>
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
            <input type="text" id="tags" name="tags" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="{{ $split_tags }}" placeholder="Enter tags separated by commas">
        </div>

        <!-- Post Options -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Post Options</label>
            <div class="flex items-center space-x-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="published" class="text-indigo-600 focus:ring-indigo-500" @if ($page->published) checked @endif>
                    <span class="ml-2">Publish</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="featured" class="text-indigo-600 focus:ring-indigo-500" @if ($page->featured) checked @endif>
                    <span class="ml-2">Featured</span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="bg-lime-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-lime-500 focus:outline-none focus:ring-2 focus:ring-lime-500">
                Update Post
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

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('preview');
            output.src = reader.result;
            output.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function copyToClipboard(text) {
        const tempInput = document.createElement('input');
        document.body.appendChild(tempInput);
        tempInput.value = text;
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        alert('Image URL copied to clipboard!');
    }
</script>

@endsection
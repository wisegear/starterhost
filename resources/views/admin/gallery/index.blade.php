@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Gallery Admin</h1>


    {{-- Add New Category --}}
    <div class="my-10">
        <div class="">
            <form action="{{ route('categories.store') }}" method="POST" class="flex space-x-2">
                @csrf
                <input type="text" name="name" placeholder="New Category Name" class="border rounded px-2 py-1 w-1/2" required>
                <button type="submit" class="px-4 py-1 bg-lime-500 text-black rounded-lg hover:bg-lime-600">Add Category</button>
            </form>
        </div>

        {{-- Add New Album --}}
        <div class="mt-6">
            <form action="{{ route('albums.store') }}" method="POST" class="flex space-x-2">
                @csrf
                <select name="category_id" class="border rounded px-2 py-1 w-2/5" required>
                    <option value="" disabled selected>Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="name" placeholder="New Album Name" class="border rounded px-2 py-1 w-2/5" required>
                <button type="submit" class="px-4 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Add Album</button>
            </form>
        </div>
    </div>

    {{-- Categories and Albums Section --}}
    <div class="my-10">
        <h2 class="text-lg font-bold mb-4">Categories and Albums</h2>
        <div class="bg-white shadow-md rounded-lg p-6">
            @foreach ($categories as $category)
            <div class="mb-6">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-lg">
                        {{ $category->name }} 
                        <span class="text-sm text-gray-500">({{ $category->albums->count() }} albums)</span>
                    </h3>
                    <div>
                        <a href="{{ route('categories.edit', $category->id) }}" class="border p-1 bg-slate-300 rounded text-black">Edit</a>
                        
                        <form id="delete-category-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            
                            <button 
                                type="submit" 
                                class="border p-1 bg-red-300 rounded text-black {{ $category->albums->isEmpty() ? '' : 'opacity-50 cursor-not-allowed' }}" 
                                {{ $category->albums->isEmpty() ? '' : 'disabled' }}
                                onclick="return {{ $category->albums->isEmpty() ? 'confirm(\'Are you sure you want to delete this category? This action cannot be undone.\')' : 'false' }};"
                            >
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
        
                <div class="ml-6">
                    @foreach ($category->albums as $album)
                        <div class="flex justify-between items-center mb-2">
                            <p>
                                {{ $album->name }}
                                <span class="text-sm text-gray-500">({{ $album->images->count() }} images)</span>
                            </p>
                            <div>
                                <a href="{{ route('albums.edit', $album->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                
                                <form id="delete-album-{{ $album->id }}" action="{{ route('albums.destroy', $album->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button 
                                        type="submit" 
                                        class="text-red-600 hover:underline {{ $album->images->isEmpty() ? '' : 'opacity-50 cursor-not-allowed' }}" 
                                        {{ $album->images->isEmpty() ? '' : 'disabled' }}
                                        onclick="return {{ $album->images->isEmpty() ? 'confirm(\'Are you sure you want to delete this album? This action cannot be undone.\')' : 'false' }};"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        </div>
    </div>

    {{-- Images Section --}}
    <div class="mb-12">
        <h2 class="text-lg font-bold mb-4">All Images</h2>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full text-sm text-left text-gray-500">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4">Image</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Album</th>
                        <th class="py-3 px-4">Title</th>
                        <th class="py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($images as $image)
                    <tr class="bg-white border-b hover:bg-gray-100">
                        <td class="py-3 px-4 align-top">
                            <img 
                                src="{{ 
                                    $image->album && $image->album->category 
                                        ? asset('storage/gallery/' . $image->album->category->name . '/' . $image->album->name . '/small_' . $image->image) 
                                        : asset('storage/default-placeholder.jpg') 
                                }}" 
                                alt="{{ $image->title }}" 
                                class="w-32 h-16">
                        </td>
                        <td class="py-3 px-4 align-top">
                            {{ $image->album && $image->album->category ? $image->album->category->name : 'N/A' }}
                        </td>
                        <td class="py-3 px-4 align-top">
                            {{ $image->album ? $image->album->name : 'N/A' }}
                        </td>
                        <td class="py-3 px-4 align-top">{{ $image->title }}</td>
                        <td class="py-3 px-4 align-top">
                            <a href="{{ route('gallery.edit', $image->id) }}" class="border p-1 bg-slate-300 rounded text-black">Edit</a>
                            <form action="{{ route('gallery.destroy', $image->id) }}" method="POST" 
                                class="inline"
                                onsubmit="return confirm('Are you sure you want to delete this image? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border p-1 bg-red-300 rounded text-black">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $images->links() }}
        </div>
    </div>
</div>

@endsection
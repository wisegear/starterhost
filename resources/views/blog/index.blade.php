@extends('layouts.app')

@section('content')

    <div class="flex flex-col flex-col-reverse md:flex md:flex-row md:space-x-10">
        
        <div class="md:w-9/12">

            <!-- Display Paginated Posts -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                @foreach ($posts as $post)
                    <div class="border p-2 shadow-lg">
                        
                        <img src="{{ asset($post->small_image) }}" class="w-full" alt="">

                        @can('Admin')
                        <div class="flex justify-end space-x-2">
                            <form action="/blog/{{ $post->id }}" method="POST" onsubmit="return confirm('Do you really want to delete this Quote?');">
                            {{ csrf_field() }}
                            {{ method_field ('DELETE') }} 
                            <button class="hover:text-red-500" role="button" type="submit">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                            </form>
                            <a class="hover:text-yellow-500" href="/blog/{{ $post->id }}/edit" role="button"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        </div>
                        @endcan
                        <h3 class="text-lg font-bold mt-4"><a href="/blog/{{ $post->slug }}">{{ $post->title }}</a></h3>
                        <ul class="flex space-x-4 text-sm mt-1">
                            <li><a href="#"><i class="fa-solid fa-user mr-2"></i>{{ $post->users->name }}</a></li>
                            <li><i class="fa-solid fa-clock mr-2"></i>{{ $post->date->diffForHumans() }}</li>
                            <li><a href="#"><i class="fa-solid fa-folder mr-2"></i>{{ $post->blogcategories->name }}</a></li>
                        </ul>
                        <p class="my-2">{{ $post->excerpt }}</p>
                        <div class="flex flex-wrap space-x-4">
                            @foreach ($post->blogtags as $tag)
                                <a href="/blog?tag={{ $tag->name }}"><button class="text-xs uppercase border p-1 bg-slate-600 rounded text-white hover:bg-slate-400">{{ $tag->name }}</button></a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10 w-2/3 mx-auto">
                {{ $posts->links() }}
            </div>

        </div>

        <div class="w-full mx-auto sm:w-1/2 md:w-3/12">
            <!-- Search -->
            <div class="mb-10 w-full">
                <form method="get" action="/blog" class="mb-5">
                    <h2 class="text-lg font-bold mb-2">Search Blog</h2>
                    <div class="relative">
                        <input type="text" class="border border-gray-300 rounded-md w-full text-sm pl-2 pr-10" id="search" name="search" placeholder="Enter search term">
                        <button class=" absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>
            <!-- Categories -->
            <div class="hidden md:block mb-10">
                <h2 class="text-lg font-bold mb-2 border-b">Categories</h2>
                @foreach ($categories as $category)
                    <ul>
                        <li><a href="/blog?category={{ Str::slug($category->name) }}">{{ $category->name }}</a></li>
                    </ul>
                @endforeach
            </div>
            <!-- Blog Tags -->
            <div class="hidden md:block my-6">
                <h2 class="text-lg font-bold border-b mb-4">Popular Tags</h2>
                @foreach ($popular_tags as $tag)
                    <button class="mr-2 mb-4">
                        <a href="/blog?tag={{ $tag->name }}" class="border p-1 text-xs uppercase bg-slate-600 text-white hover:bg-slate-400 hover:text-white rounded">{{ $tag->name }}</a>
                    </button>
                @endforeach
            </div>
            @can('Admin')
            <!-- Admin -->
            <div class="hidden md:block my-6">
             <h2 class="text-xl font-bold text-gray-700 border-b border-gray-300 mb-4"><i class="fa-solid fa-user-secret text-red-800"></i> Admin Tools</h2>
                <div class="flex justify-center">
                    <p class="border p-1 bg-lime-400 rounded text-sm"><a href="/blog/create">Create New Post</a></p>
                </div>
                <div class="flex flex-col space-y-2 text-sm mt-4">
                    @foreach ($unpublished as $post)
                        <a href="../blog/{{$post->id}}/edit" class="text-gray-700 hover:text-sky-700">{{ $post->title }}</a>
                    @endforeach
                </div>
            </div> 
           @endcan 
        </div>

    </div>

@endsection
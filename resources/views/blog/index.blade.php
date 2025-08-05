@extends('layouts.app')

@section('content')

    <div class="my-10">
        <h2 class="tracking-tight text-4xl text-zinc-500 font-bold md:w-8/12">The blog gives a range of useful hints, tips and updates to support your hosting.</h2>
    </div>

    <div class="flex flex-col-reverse md:flex md:flex-row md:space-x-10">
        
        <div class="md:w-9/12">

            <!-- Display Paginated Posts -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                @foreach ($posts as $post)
                    <div class="border rounded-lg p-2 shadow-lg border border-zinc-300">
                        <div class="relative w-full">
                            <img src="{{ asset('storage/images/blog/small_' . $post->image) }}" class="w-full rounded-md" alt="{{ $post->title }}">
                            <!-- Show featured badge if the 'featured' field is true -->
                            @if($post->featured)
                                <span 
                                    class="absolute top-2 right-2 bg-lime-600 text-white 
                                        text-xs font-semibold px-2 py-1 rounded"
                                >
                                <i class="fa-solid fa-star"></i> Featured
                                </span>
                            @endif
                        </div>
                        @can('Admin')
                        <div class="flex justify-end space-x-2">
                            <form action="/blog/{{ $post->id }}" method="POST" onsubmit="return confirm('Do you really want to delete this Post?');">
                            {{ csrf_field() }}
                            {{ method_field ('DELETE') }} 
                            <button class="hover:text-red-500 dark:text-white" role="button" type="submit">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                            </form>
                            <a class="hover:text-yellow-500 dark:text-white" href="/blog/{{ $post->id }}/edit" role="button"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        </div>
                        @endcan
                        <h3 class="text-lg font-bold mt-4 text-zinc-500"><a href="/blog/{{ $post->slug }}">{{ $post->title }}</a></h3>
                        <ul class="flex space-x-4 text-sm text-zinc-400 mt-1">
                            <li><a href="/profile/{{ $post->users->name_slug }}"><i class="fa-solid fa-user mr-2"></i>{{ $post->users->name }}</a></li>
                            <li><i class="fa-solid fa-clock mr-2"></i>{{ $post->date->diffForHumans() }}</li>
                            <li><a href="/blog?category={{ $post->blogCategories->name }}"><i class="fa-solid fa-folder mr-2"></i>{{ $post->blogcategories->name }}</a></li>
                        </ul>
                        <p class="my-2 text-zinc-500">{{ $post->summary }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($post->blogtags as $tag)
                                <a href="/blog?tag={{ $tag->name }}"><button class="border border-zinc-300 rounded text-sm p-1 bg-zinc-200 hover:bg-zinc-300 hover:text-black cursor-pointer mr-2">{{ $tag->name }}</button></a>
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

        <!-- Sidebar -->

        <div class="w-full mx-auto sm:w-1/2 md:w-3/12">
            <!-- Search -->
            <div class="mb-10 w-full">
                <form method="get" action="/blog" class="mb-5">
                    <h2 class="text-lg font-bold mb-2">Search Blog</h2>
                    <div class="relative">
                        <input type="text" class="border border-gray-300 rounded-md w-full text-sm p-2 pr-10" id="search" name="search" placeholder="Enter search term">
                        <button class=" absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>
            <!-- Categories -->
            <div class="hidden md:block mb-10">
                <h2 class="text-lg font-bold mb-2 border-b border-zinc-300">Categories</h2>
                @foreach ($categories as $category)
                    <ul>
                        <li><a href="/blog?category={{ Str::slug($category->name) }}">{{ $category->name }}</a></li>
                    </ul>
                @endforeach
            </div>
            <!-- Blog Tags -->
            <div class="hidden md:block my-6">
                <h2 class="text-lg font-bold border-b border-zinc-300 mb-4">Popular Tags</h2>
                <div class="flex flex-wrap">
                    @foreach ($popular_tags as $tag)
                        <a href="/blog?tag={{ $tag->name }}" class=""><button class="border border-zinc-300 rounded text-sm p-1 bg-zinc-200 hover:bg-zinc-300 hover:text-black cursor-pointer mb-2 mr-4">{{ $tag->name }}</button></a>
                    @endforeach
                </div>
            </div>
            <!-- Featured Posts -->
            <div class="hidden md:block my-6">
                <h2 class="text-lg font-bold border-b border-zinc-300 mb-4">Featured Posts</h2>
                @foreach( $featured as $feature )
                    <div class="mb-4">
                        <div class="">
                            <h3 class=""><a href="../blog/{{$feature->slug}}"> {{$feature->title}} </a></h3>
                        </div>
                    </div> 
                @endforeach
            </div>
            @can('Admin')
            <!-- Admin -->
            <div class="hidden md:block my-6">
             <h2 class="text-xl font-bold text-gray-700 dark:text-red-300 border-b border-zinc-300 mb-4"><i class="fa-solid fa-user-secret text-red-800"></i> Admin Tools</h2>
                <div class="flex justify-center">
                    <p class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition"><a href="/blog/create">Create New Post</a></p>
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
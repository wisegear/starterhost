@extends('layouts.app')

@section('content')

    <div class="flex justify-between space-x-10 my-10">

        <!-- Display Gallery Image -->
        <div class="w-9/12">
            <div class="">
                <div class="mb-4">
                    <h2 class="font-bold text-2xl text-center mb-2">{{ $page->title }}</h2>
                    <p class="w-3/4 mx-auto text-center text-sm text-gray-600">{{ $page->summary }}</p>
                </div>
                <div class="border">
                    <img class="w-full rounded shadow-lg" src="{{ asset('/storage/images/gallery/' . $page->category->name . '/' . $page->album->name . '/' . 'large_' . $page->image) }}" alt="{{ $page->title }}">
                </div>
                <div class="mt-4">
                    <ul class="flex justify-center space-x-10">
                        <li class=""><i class="fa-solid fa-clock mr-2"></i> {{ $page->date_taken }}</li>
                        <li class=""><i class="fa-solid fa-location-crosshairs mr-2"></i> {{ $page->location }}</li>
                        <li class=""><a href="{{ asset('/storage/images/gallery/' . $page->category->name . '/' . $page->album->name . '/' . $page->image) }}" 
                            target="_blank" class=""><i class="fa-solid fa-image"></i> Open Original Image*</a>
                        </li>
                    </ul>
                </div>
                <div class="my-4 text-center">
                    {{ $page->text }}
                </div>
                <div class="flex justify-center space-x-4 mt-4">
                    @foreach($page->tags as $tag)
                        <div class="wise-button-sm">
                            <a href="../gallery?tag={{ $tag->name }}">{{ $tag->name }}</a>
                        </div>
                    @endforeach     
                </div>
                <div class="text-sm my-10 text-slate-500 text-center">
                    <p>*if you click the link to open the original image be aware that some are several
                    megabytes in size.  They may open slowly depending on your internet speed.  Be morer cautious of doing this on mobile devices with limited data, it could use up
                    your data quite quickly.</p>
                </div>
            </div>
            <div class="my-10">
             <!-- Comments Section -->
            @include('comments', ['comments' => $page->comments, 'model' => $page])
            </div>
        </div>

        <div class="md:w-3/12">
            <!-- Find images based on user search input -->
            <div class="mb-10">
                <form method="get" action="/gallery" class="">
                    <h2 class="text-lg font-bold mb-2">Search Images</h2>
                    <div class="relative">
                        <input type="text" class="border border-gray-300 rounded-md w-full text-sm pl-2 pr-10" id="search" name="search" placeholder="Enter search term">
                        <button class=" absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>
            <!-- Gallery Categories -->
            <div class="mb-10">
                <h2 class="border-b font-bold text-lg mb-4">Categories</h2> 
                <ul>
                    @foreach( $categories as $category )
                        <li class=""><a href="../gallery?category={{ $category->name }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <!-- Popular Tags -->
            <div class="mb-10">
                <h2 class="border-b font-bold text-lg mb-4">Popular Tags</h2> 
                @foreach ($popularTags as $tag)
                    <div class="inline-flex pb-2 pr-2">
                        <a href="../gallery?tag={{ $tag->name }}" class="wise-button-sm">{{ $tag->name }}</a>
                    </div>
                @endforeach
            </div>
            <div class="mb-10">
            <!-- View Admin for Gallery -->
                @can('Admin')
                    <div class="mb-10">
                        <h2 class="text-lg font-bold mb-4 text-red-800 border-b"><i class="fa-solid fa-user-secret"></i> Admin</h2>
                        <div class="flex justify-center mb-10">
                            <a href="/gallery/create"><button class="wise-button-md">Create New Image</button></a>
                        </div>
                        @foreach ($unpublished as $item)
                            <div class="flex justify-between mb-2">
                                <div>
                                    <a href="/gallery/{{ $item->id }}/edit">{{ $item->title }}</a>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <form action="/gallery/{{ $item->id }}" method="POST" onsubmit="return confirm('Do you really want to delete this Quote?');">
                                    {{ csrf_field() }}
                                    {{ method_field ('DELETE') }} 
                                    <button class="hover:text-red-500" role="button" type="submit">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                    </form>
                                    <a class="hover:text-yellow-500" href="/gallery/{{ $item->id }}/edit" role="button"><i class="fa-solid fa-pen-to-square"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endcan                
            </div>
        </div>

    </div>


@endsection
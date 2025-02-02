@extends('layouts.app')

@section('content')

    <div class="relative flex flex-col justify-center items-center h-[150px] border mb-10 bg-gray-100 shadow-lg">
        <i class="fa-solid fa-images fa-6x fa-rotate-by text-slate-200 absolute left-5" style="--fa-rotate-angle: 25deg;"></i>
        <h2 class="font-bold text-center text-2xl z-10">Image Gallery</h2>
        <p class="text-center text-gray-500 z-10">Images from the holocaust both past and present</p>
        <i class="fa-solid fa-images fa-6x fa-rotate-by text-slate-200 absolute right-5" style="--fa-rotate-angle: -25deg;"></i>
    </div>

    <div class="flex flex-col md:flex-row justify-between md:space-x-10 my-10">

        <div class="md:w-9/12">
            <!-- Header notes -->
            @if (isset($_GET['search']))
                <div>
                    <h2 class="text-lg font-bold mb-2">You searched for <span class="text-sky-600 italic">{{ $_GET['search'] }}</span>.  If there are any images they are shown below.</h2>
                    <p class="border-b text-sm pb-2">When searching for images the search term used is checked against the title, summary and text of the images for a match. If there is
                        no images below then nothing was found using the search term <span class="text-sky-600 italic">{{ $_GET['search'] }}</span>.
                    </p>
                </div>
            @elseif (isset($_GET['category']))
                <div>
                    <h2 class="text-lg font-bold mb-2">You are viewing the <span class="text-sky-600 italic">{{ $_GET['category'] }}</span> category.  Albums related to this category are 
                    shown below.</h2>
                    <p class="border-b text-sm pb-2">Each category has a numebr of albums.  Clicking on one of the albums below will display all of the images related to that particular album.</p>
                </div>
            @elseif (isset($_GET['album']))
                <div>
                    <h2 class="text-lg font-bold mb-2">You are viewing the <span class="text-sky-600 italic">{{ $_GET['album'] }}</span> album.  Images related to this album are 
                    shown below.</h2>
                    <p class="border-b text-sm pb-2">Each album has a number of images.  Clicking on one of the images below will display it.</p>
                </div>            
            @elseif (isset($_GET['tag']))
                <div>
                    <h2 class="text-lg font-bold mb-2">You searched for the <span class="text-sky-600 italic">{{ $_GET['tag'] }}</span> tag.  Images related to this tag are 
                    shown below.</h2>
                    <p class="border-b text-sm pb-2">To help finding images you are interested in each has at least one tag specific to that image.  The image may be assigned to many images
                        so you may see more than one image below.
                    </p>
                </div>                
            @else
                <div>
                    <h2 class="text-lg font-bold mb-2">Most recent images added to the gallery</h2>
                    <p class="border-b text-sm pb-2">The images below are the most recent ones added to the gallery. 
                        For more specific images use the search box or use the categories to find a more general set of images related to a specific area of the holocaust.</p>
                </div>
            @endif

            <!-- Display output based on the user request -->

            @if (isset($_GET['category'])) <!-- Display Albums -->
                <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 my-4">
                    @foreach ($results as $album)
                        <div class="border p-1 relative bg-gray-50">
                            <a class="" href="/gallery?album={{ \Illuminate\Support\Str::slug($album->name) }}"> 
                                <img class="w-full object-cover h-[150px]" src="{{ asset('storage/images/gallery/' . $album->category->name . '/' . $album->name . '/small_' . $album->images()->inRandomOrder()->first()?->image) }}" 
                                     alt="{{ $album->name }}">
                            </a>
                            <p class="absolute top-5 right-0 bg-gray-300 text-xs p-1 shadow-md">{{ $album->category->name }}</p>
                            <p class="text-xs p-2 text-center text-gray-500 bg-gray-50">{{ $album->name }}</p>
                        </div>
                    @endforeach
                </div>                
            @else <!-- Display images -->
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 my-4">
                @foreach ($results as $image)
                    <div class="border relative bg-gray-50 flex flex-col">
                        <!-- Link and Image -->
                        <a href="/gallery/{{$image->slug}}">
                            <img class="w-full object-cover h-[150px]" src="{{ asset('storage/images/gallery/' . $image->category->name . '/' . $image->album->name . '/small_' . $image->image) }}" alt="{{ $image->title }}">
                        </a>
                        
                        <!-- Title -->
                        <p class="text-xs p-2 text-center text-gray-800 bg-gray-50">{{ $image->title }}</p>
                        
                        <!-- Album Badge -->
                        <div class="absolute top-2 right-1 border border-gray-400 text-sm bg-slate-500 text-white font-semibold">
                            <a href="../gallery?album={{ \Illuminate\Support\Str::slug($image->album->name) }}">
                                <p class="px-1">{{ $image->album->name }}</p>
                            </a>
                        </div>
                        @can('Admin')    
                        <div class="flex justify-end space-x-2">
                            <form action="/gallery/{{ $image->id }}" method="POST" onsubmit="return confirm('Do you really want to delete this Image?');">
                            {{ csrf_field() }}
                            {{ method_field ('DELETE') }} 
                            <button class="hover:text-red-500 dark:text-white" role="button" type="submit">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                            </form>
                            <a class="hover:text-yellow-500 dark:text-white" href="/gallery/{{ $image->id }}/edit" role="button"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        </div>
                        @endcan

                    </div>
                @endforeach
            </div>
            @endif

            <div class="flex justify-center my-10">
                {{ $results->links() }}
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
                        <li class=""><a href="../gallery?category={{ \Illuminate\Support\Str::slug($category->name) }}">{{ $category->name }}</a></li>
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
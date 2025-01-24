@extends('layouts.app')

@section('content')

    <div class="flex justify-between space-x-10 my-10">

        <div class="w-9/12">
            <!-- Header notes -->
            @if (isset($_GET['search']))
                <div>
                    <h2 class="text-lg font-bold mb-2">You searched for <span class="text-red-500 italic">{{ $_GET['search'] }}</span>.  If there are any images they are shown below.</h2>
                    <p class="border-b text-sm">When searching for images the search term used is checked against the title, summary and text of the images for a match. If there is
                        no images below then nothing was found using the search term <span class="text-red-500 italic">{{ $_GET['search'] }}</span>.
                    </p>
                </div>
            @elseif (isset($_GET['category']))
                <div>
                    <h2 class="text-lg font-bold mb-2">You are viewing the <span class="text-red-500 italic">{{ $_GET['category'] }}</span> category.  Albums related to this category are 
                    shown below.</h2>
                    <p class="border-b text-sm">Each category has a numebr of albums.  Clicking on one of the albums below will display all of the images related to that particular album.</p>
                </div>
            @elseif (isset($_GET['album']))
                <div>
                    <h2 class="text-lg font-bold mb-2">You are viewing the <span class="text-red-500 italic">{{ $_GET['album'] }}</span> album.  Images related to this album are 
                    shown below.</h2>
                    <p class="border-b text-sm">Each album has a number of images.  Clicking on one of the images below will display it.</p>
                </div>            
            @elseif (isset($_GET['tag']))
                <div>
                    <h2 class="text-lg font-bold mb-2">You searched for the <span class="text-red-500 italic">{{ $_GET['tag'] }}</span> tag.  Images related to this tag are 
                    shown below.</h2>
                    <p class="border-b text-sm">To help finding images you are interested in each has at least one tag specific to that image.  The image may be assigned to many images
                        so you may see more than one image below.
                    </p>
                </div>                
            @else
                <div>
                    <h2 class="text-lg font-bold mb-2">Most recent images added to the gallery</h2>
                    <p class="border-b text-sm">The images below are the most recent ones added to the gallery. 
                        For more specific images use the search box or use the categories to find a more general set of images related to a specific area of the holocaust.</p>
                </div>
            @endif

            <!-- Display output based on the user request -->

            @if (isset($_GET['category'])) <!-- Display Albums -->
                <div class="grid grid-cols-4 gap-4 my-4">
                    @foreach ($results as $album)
                        <div class="border p-1 relative bg-gray-50 h-full">
                            <a class="" href="/gallery?album={{$album->name}}"> <img src="{{ $album->GalleryImages()->inRandomOrder()->first()?->image }}" alt="{{ $album->name }}"></a>
                            <p class="absolute top-5 right-0 bg-gray-300 text-xs p-1 shadow-md">{{ $album->GalleryCategory->name }}</p>
                            <p class="text-xs p-2 text-center text-gray-500 bg-gray-50">{{ $album->name }}</p>
                        </div>
                    @endforeach
                </div>                
            @else <!-- Display images -->
                <div class="grid grid-cols-4 gap-4 my-4">
                    @foreach ($results as $image)
                        <div class="border p-1 relative bg-gray-50 h-full">
                            <a class="" href="/gallery/{{$image->slug}}"><img src="{{ $image->image }}" alt=""></a>
                            <a href="../gallery?album={{ $image->GalleryAlbum->name }}"><p class="absolute top-5 right-0 bg-gray-300 text-xs p-1 shadow-md">{{ $image->GalleryAlbum->name }}</p></a>
                            <p class="text-xs p-2 text-center text-gray-500 bg-gray-50">{{ $image->title }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-center my-10">
                {{ $results->links() }}
            </div>
        </div>

        <div class="w-3/12">
            <!-- Image count -->
            <div class="flex flex-col items-center justify-center  mb-4 border shadow-lg p-2">
                <h3 class="font-bold text-lg">Total Gallery Images</h3>
                <p class="text-slate-500 font-bold">{{ $imageTotal }}</p>        
            </div>
            <!-- Gallery Search Box -->
            <div class="py-4 w-full mx-auto">
                <form action="/gallery">
                    <input type="text" class="shadow-lg p-2 border-gray-300 w-full hover:border-gray-600" name="search" id="search" placeholder="Search for images..">
                </form>
            </div>
            <!-- Gallery Categories -->
            <div class="py-4">
                <h2 class="border-b font-bold text-lg mb-4">Categories</h2> 
                <ul>
                    @foreach( $categories as $category )
                        <li class=""><a href="../gallery?category={{ $category->name }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <!-- Popular Tags -->
            <div class="">
                <h2 class="border-b font-bold text-lg mb-4">Popular Tags</h2> 
                @foreach ($popularTags as $tag)
                    <div class="flex inline-flex pb-2 pr-2">
                        <a href="../gallery?tag={{ $tag->name }}" class="border rounded py-1 px-2 text-sm bg-slate-300 hover:bg-gray-100 hover:text-red-800 hover:border-gray-300">{{ $tag->name }}</a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>


@endsection
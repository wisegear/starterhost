@extends('layouts.app')
@section('content')
    <!-- Content Section -->
    <div class="flex-grow my-10">
        <!-- Hero Section -->
        <div class="border rounded p-5">
            <div class="flex justify-between items-center space-x-10">
                <div class="w-5/12">
                  <img src="../assets/images/site/auschwitz-gate.jpg" alt="Gate at Auschwitz" class="w-full max-h-[250px]">
                </div>
                <div class="w-7/12">
                    <i class="fa-solid fa-quote-left text-slate-500"></i>
                    <p class="italic text-gray-500">
                    There's a long road of suffering ahead of you. But don't lose courage. You've already escaped the gravest danger: selection. So now, muster your strength, and don't lose heart. We shall all see the day of liberation. Have faith in life. Above all else, have faith. Drive out despair, and you will keep death away from yourselves. Hell is not for eternity. And now, a prayer - or rather, a piece of advice: let there be comradeship among you. We are all brothers, and we are all suffering the same fate. The same smoke floats over all our heads. Help one another. It is the only way to survive.</p>
                    <p class="text-right text-slate-500 font-semibold text-sm">-- Elie Wiesel </p>
                </div>
            </div>
        </div>
         <!-- Outer content section -->
         <div class="mt-10">
            <!-- left and right content -->
            <div class="flex justify-between space-x-10">
                <!-- left -->
                <div class="w-9/12">
                    <h2 class="font-bold text-lg border-b mb-4">Welcome</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aspernatur reprehenderit vero nulla id temporibus, 
                        voluptatem veniam porro laborum rem recusandae neque nostrum. Omnis corporis id placeat illo non magnam eos!
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut fugiat, sint ipsum iure assumenda, atque nam placeat porro adipisci, 
                        recusandae error sed ullam! Similique ratione expedita officia alias quis magnam?</p>
                </div>
                <!-- right -->
                <div class="w-3/12">
                    <div class="mb-10">
                        <h2 class="border-b text-lg font-bold mb-4">Random Gallery Images</h2>
                        <!-- For each loop -->
                        @foreach ($gallery as $image)
                            <div class="relative">
                                <a href="../gallery/{{ $image->slug }}" class=""><img class="mb-4 h-full w-full border rounded p-2" src="{{ $image->image }}" alt="{{ $image->title }}"></a>     
                                <div class="absolute top-5 right-0 border text-sm bg-slate-500 text-white px-2 font-semibold">
                                   <a href="../gallery?album={{ $image->GalleryAlbum->name }}"><p>{{ $image->GalleryAlbum->name }}</p></a>
                                </div>  
                            </div>   
                        @endforeach
                        <!-- End foreach -->
                    </div>
                    <div class="">
                        <h2 class="border-b text-lg font-bold mb-4">Recent Blog Posts</h2>
                        @foreach ( $posts as $post)
                            <div class="">
                                <h3 class="font-semibold text-slate-500 hover:text-yellow-700 text-lg"><a href="../blog/{{$post->slug}}"> {{$post->title}} </a></h3>
                                <div class="mb-4">
                                    <ul class="flex space-x-4 text-sm">
                                        <li class=""> {{$post->date->diffForHumans()}} </li>
                                        <li class="">{{$post->blogcategories->name}}</li>
                        </ul>
                                    <p class="text-sm mt-2"> {{$post->exceprt}} </p>
                                </div>
                            </div>            
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
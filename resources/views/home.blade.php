@extends('layouts.app')

@section('content')
    <!-- Content Section -->
    <div class="flex-grow my-10">

        <!-- Hero Section -->
        <div class="border rounded p-5">
            <!-- Stack vertically on small screens, horizontal on md+ -->
            <div class="flex flex-col md:flex-row justify-between items-center md:space-x-10 space-y-6 md:space-y-0">
                <div class="md:w-4/12">
                    <img 
                        src="../assets/images/site/auschwitz-gate.jpg" 
                        alt="Gate at Auschwitz" 
                        class="w-full max-h-[250px] object-cover"
                    >
                </div>
                <div class="md:w-8/12">
                    <h2 class="font-bold text-lg border-b mb-4">Welcome</h2>
                    <p>
                        HolocaustResearch.net is a personal website that I have been adding to for many years using 
                        an internet blogging system that I had no real control over and had many limitations. 
                        So I decided to buy a domain name, learn how to code and build something that meets my needs. 
                        This is it. It may not be the best but it's better than what I had. The site went live 
                        on the 27th Jan 2025. It will take time to update with all my information, so depending 
                        on when you visit it may have little to no information or be nearly complete!
                    </p>
                </div>
            </div>
        </div>

        <!-- Outer content section -->
        <div class="mt-10">
            <!-- Stack vertically on small screens; side-by-side on lg+ -->
            <div class="flex flex-col lg:flex-row lg:space-x-10 space-y-10 lg:space-y-0">

                <!-- Left Content -->
                <div class="lg:w-9/12">
                    <!-- Recent Blog Posts -->
                    <div class="mb-4">
                        <h2 class="border-b text-lg font-bold mb-4">Recent Blog Posts</h2>

                        <!-- Responsive grid: 1 column on mobile, 2 on md, 4 on lg -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach ($posts as $post)
                                <div class="border mb-4 shadow-lg">
                                    <img 
                                        src="{{ asset('storage/images/blog/small_' . $post->image) }}" 
                                        class="p-2 w-full object-cover"
                                        alt="{{ $post->title }}"
                                    >
                                    <div class="p-2">
                                        <h3 class="font-bold">
                                            <a href="../blog/{{ $post->slug }}">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <div class="mb-4">
                                            <ul class="flex space-x-4 text-sm mt-2">
                                                <li class="">
                                                    <i class="fa-solid fa-clock text-slate-400"></i> 
                                                    {{ $post->date->diffForHumans() }}
                                                </li>
                                                <li class="">
                                                    <i class="fa-solid fa-folder text-slate-400"></i> 
                                                    <a href="/blog?category={{ $post->blogcategories->name }}">
                                                        {{ $post->blogcategories->name }}
                                                    </a>
                                                </li>
                                            </ul>
                                            <p class="text-sm mt-2">
                                                {{ Str::words($post->summary, 20, '...') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Content / Sidebar -->
                <div class="lg:w-3/12">
                    <!-- Random Quote -->
                    <div>
                        <h2 class="font-bold text-lg border-b mb-4">Random Quote</h2>
                        <i class="fa-solid fa-quote-left text-slate-500"></i>
                        <p class="italic text-gray-500">{{ $quote->text }}</p>
                        <p class="text-right text-slate-500 font-semibold text-sm pt-4">
                            -- {{ $quote->author }}
                        </p>
                    </div>

                    <!-- Recent Gallery Images -->
                    <div class="my-6">
                        <h2 class="font-bold text-lg border-b mb-4">Recent Gallery Images</h2>
                        <!-- Single column for simplicity; change to multiple if you prefer -->
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-2 gap-4">
                            @foreach ($gallery as $image)
                                <div class="relative border p-2 border-gray-200 shadow-lg">
                                    <a href="../gallery/{{ $image->slug }}">
                                        <img
                                            class="h-32 w-full object-cover"
                                            src="{{ asset('storage/images/gallery/' . $image->category->name . '/' . $image->album->name . '/small_' . $image->image) }}"
                                            alt="{{ $image->title }}"
                                        >
                                    </a>
                                    <div class="absolute top-2 right-2 border border-gray-400 text-sm bg-slate-500 text-white font-semibold">
                                        <a href="../gallery?album={{ $image->album->name }}">
                                            <p class="px-1">
                                                {{ $image->album->name }}
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div> <!-- /End Right Content -->

            </div>
        </div>
    </div>
@endsection
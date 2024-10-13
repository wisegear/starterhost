@extends('layouts.app')

@section('content')

<!-- Article Header Section -->

<div class="">    
    <h1 class="text-4xl font-bold text-center dark:text-white">{{$article->title}}</h1>
        <ul class="my-4 flex flex-col md:flex-row items-center md:justify-center md:space-y-0 space-y-4 md:space-x-10">
            <li><a href="/profile/{{ $article->user->name_slug }}" class="text-gray-700 hover:text-sky-700 dark:text-white"><i class="fa-solid fa-user mr-2"></i>{{ $article->user->name }}</a></li>
            <li class="text-gray-700 dark:text-white"><i class="fa-solid fa-calendar-days mr-2 "></i>{{ $article->date->diffForHumans() }}</li>
            <li class="text-gray-700 dark:text-white"><i class="fa-solid fa-folder mr-2"></i>{{ $article->articles->name }}</li>
        </ul>    
    <p class="text-center md:w-1/2 mx-auto text-gray-500 mb-10 dark:text-gray-300">{{$article->summary }}</p>

    <img src="{{ asset($article->large_image) }}" class="w-full max-h-[350px] shadow-lg border dark:border-gray-700 rounded p-1" alt="">
</div>

<!-- Main article section split by flex of main content and sidebar content -->

<div class="flex flex-col md:flex-row mt-10 space-x-10">
    <!-- Main Content -->
    <div class="w-full">
        <div class="w-1/2 mx-auto mb-10">
            @if(count($article->getBodyHeadings('h2')) > 2)
                <div class="toc">
                    <h2 id="toc-title" class="mb-2 border-b border-gray-300 cursor-pointer">
                        <i class="fa-solid fa-arrow-down-short-wide text-lime-600 mr-2"></i><span class="font-bold">Table of contents</span>
                        <span id="toc-arrow" class="ml-2 transform transition-transform duration-200"></span>
                    </h2>
                    <ul id="toc-list" class="space-y-2 hidden">
                        @foreach($article->getBodyHeadings('h2') as $heading)
                            <li><a href="#{{ Str::slug($heading) }}" class="hover:text-blue-700">{{ $heading }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Post Text, separate from other content due to Wise1Text -->

        <div class="wise1text">
            {!! $article->addAnchorLinkstoHeadings() !!}
        </div>

        <!-- Author Box -->

            <!-- Author Box -->

            <div class="flex flex-col md:flex-row items-center rounded shadow-lg border bg-lime-100 dark:bg-gray-600 dark:border-gray-700 p-4 my-10 space-y-2">
                <div class="w-3/12 lg:w-2/12">
                    <img src="/assets/images/avatars/{{ $article->user->avatar }}" class="mx-auto rounded-full h-20 border border-gray-400 p-1">
                </div>
                <div class="w-full lg:w-10/12">
                    <p class="font-bold text-center text-gray-700 dark:text-gray-300">{{ $article->user->name }}</p>
                    @if (empty($article->user->bio))
                    <!-- If no user Bio -->
                    <p class="text-center">User has not provided any information about themselves.</p>
                    @else
                        <!-- display user Bio -->
                        <p class="text-center text-sm dark:text-gray-200">{{ $article->user->bio }}</p>
                    @endif   
                </div>
            </div>

        @include('comments', ['comments' => $article->comments, 'model' => $article])

    </div>
    <!-- Sidebar Content -->
    <div class="hidden md:block md:w-3/12 space-y-6">
        <div>
            <h2 class="font-bold text-xl border-b dark:border-b-gray-600 dark:text-white">{{ $article->articles->name }}</h2>
            <ol class="my-2 list-decimal list-inside dark:text-white">
                @foreach ($allArticles as $item)
                    <li><a href="/article/{{ $item->slug }}" class="dark:text-white mb-2">{{ $item->title }}</a></li>
                @endforeach
            </ol>
        </div>
        <div class="space-y-6">
            <h2 class="font-bold text-xl border-b dark:border-b-gray-600 dark:text-white">Recent Blog Posts</h2>
            @foreach ($posts as $post)
                <div class="">
                    <img src="{{ asset($post->small_image) }}" class="h-[150px] w-full rounded border dark:border-gray-600 p-1" alt="Post Image">
                    <h3 class="font-bold mt-2 dark:text-white"><a href="/blog/{{ $post->slug }}">{{ $post->title }}</a></h2>
                </div>               
            @endforeach

        </div>
    </div>

</div>

<script>

// This handles the dropdown of the table of contents

    document.addEventListener("DOMContentLoaded", function () {
        const tocTitle = document.getElementById('toc-title');
        const tocList = document.getElementById('toc-list');
        const tocArrow = document.getElementById('toc-arrow');

        // Toggle the visibility of the table of contents
        tocTitle.addEventListener('click', function () {
            tocList.classList.toggle('hidden'); // Show or hide the TOC list
            tocArrow.classList.toggle('rotate-90'); // Rotate the arrow indicator
        });
    });

</script>

@endsection
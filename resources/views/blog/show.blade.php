@extends('layouts.app')

@section('content')

    <!-- Post Header Section -->
    <div class="mb-10">    
        <h1 class="text-4xl font-bold text-center dark:text-white">{{ $page->title }}</h1>
        <ul class="flex flex-col md:flex-row items-center md:justify-center md:space-y-0 md:space-x-10 space-y-2 my-4">
            <li>
                <a href="/profile/{{ $page->users->name_slug }}" class="text-gray-700 dark:text-gray-400 hover:text-sky-700">
                    <i class="fa-solid fa-user mr-2"></i>{{ $page->users->name }}
                </a>
            </li>
            <li class="text-gray-700 dark:text-gray-400">
                <i class="fa-solid fa-calendar-days mr-2"></i>{{ $page->date->format('d-m-Y') }}
            </li>
            <li>
                <a href="/blog?category={{ $page->blogcategories->name }}" class="text-gray-700 dark:text-gray-400 hover:text-sky-700">
                    <i class="fa-solid fa-folder mr-2"></i>{{ $page->blogcategories->name }}
                </a>
            </li>
        </ul>    
        <p class="text-center md:w-1/2 mx-auto text-gray-500 dark:text-gray-300">{{ $page->summary }}</p>

        <!-- Tags -->
        <div class="flex flex-wrap space-x-4 my-6 justify-center">
            @foreach ($page->blogtags as $tag)
                <a href="/blog?tag={{ $tag->name }}" class="p-1 text-xs uppercase border dark:border-gray-700 rounded font-bold bg-slate-600 text-white hover:bg-slate-400 hover:text-white">
                    {{ $tag->name }}
                </a>
            @endforeach
        </div>

        <!-- Featured Image -->
        <img src="{{ asset($page->large_image) }}" class="w-full h-[400px] shadow-lg border dark:border-gray-700 rounded" alt="">
    </div>

    <!-- Main Content Section -->
    <div class="flex flex-col lg:flex-row space-y-10 lg:space-y-0 lg:space-x-10">
        <!-- Post Content -->
        <div class="lg:w-9/12">
            <!-- Table of Contents -->
            @if(count($page->getBodyHeadings('h2')) > 2)
                <div class="toc my-10">
                    <h2 id="toc-title" class="mb-2 border-b border-gray-300 cursor-pointer dark:text-white">
                        <i class="fa-solid fa-arrow-down-short-wide text-blue-600"></i> Table of contents
                        <span id="toc-arrow" class="ml-2 transform transition-transform duration-200"></span>
                    </h2>
                    <ul id="toc-list" class="space-y-2 hidden">
                        @foreach($page->getBodyHeadings('h2') as $heading)
                            <li>
                                <a href="#{{ Str::slug($heading) }}" class="hover:text-blue-700 dark:text-white">{{ $heading }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Post Body -->
            <div class="wise1text mt-10">
                {!! $page->addAnchorLinkstoHeadings() !!}
            </div>

            <!-- Share Buttons -->
            <div class="mt-10 text-center">
                <a href="#blank">
                    <button class="border border-gray-300 dark:border-gray-600 dark:text-gray-300 p-1 text-gray-500 text-xs font-bold py-1 px-2 mr-2">
                        Share
                    </button>
                </a>

                <!-- Twitter Share -->
                <a href="http://twitter.com/share?url={{ url()->current() }}&text={{ urlencode($page->title) }}">
                    <button id="social-button" aria-label="twitter-button" class="border border-gray-300 py-1 px-2 text-indigo-500 text-xs mr-2 dark:border-gray-600 hover:border-gray-400">
                        <i class="fa-brands fa-twitter text-[#1da1f2]"></i>
                    </button>
                </a>

                <!-- LinkedIn Share -->
                <a href="http://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}&title={{ urlencode($page->title) }}">
                    <button id="social-button" aria-label="linkedin-button" class="border border-gray-300 p-1 text-indigo-500 text-xs py-1 px-2 mr-2 dark:border-gray-600 hover:border-gray-400">
                        <i class="fa-brands fa-linkedin-in text-[#0a66c2]"></i>
                    </button>
                </a>

                <!-- Facebook Share -->
                <a href="http://www.facebook.com/sharer.php?u={{ url()->current() }}">
                    <button id="social-button" aria-label="facebook-button" class="border border-gray-300 p-1 text-indigo-500 text-xs py-1 px-2 dark:border-gray-600 hover:border-gray-400">
                        <i class="fa-brands fa-facebook-f text-[#1877f2]"></i>
                    </button>
                </a>
            </div>

            <!-- Author Box -->
            <div class="flex items-center rounded shadow-lg border bg-lime-50 dark:bg-gray-600 dark:border-gray-700 p-4 my-10 space-y-2">
                <div class="w-3/12 lg:w-2/12">
                    <img src="/assets/images/avatars/{{ $page->users->avatar }}" class="mx-auto rounded-full h-20 border border-gray-400 p-1">
                </div>
                <div class="w-full lg:w-10/12">
                    <p class="font-bold text-center text-gray-700 dark:text-gray-300">{{ $page->users->name }}</p>
                    @if (empty($page->users->bio))
                        <p class="text-center">User has not provided any information about themselves.</p>
                    @else
                        <p class="text-center text-sm dark:text-gray-200">{{ $page->users->bio }}</p>
                    @endif
                </div>
            </div>

            <!-- Comments Section -->
            @include('comments', ['comments' => $page->comments, 'model' => $page])
        </div>

        <!-- Sidebar Section -->
        <div class="hidden lg:block lg:w-3/12 mt-10">
            <h2 class="text-xl font-bold mb-4 dark:text-white">Recent Posts:</h2>
            @foreach ($recentPages as $recentPage)
                <div class="mb-6">
                    <img src="{{ asset($recentPage->small_image) }}" class="h-[150px] w-full rounded border dark:border-gray-700 p-1" alt="">
                    <h3 class="font-bold mt-2 dark:text-white">
                        <a href="/blog/{{ $recentPage->slug }}">{{ $recentPage->title }}</a>
                    </h3>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Table of Contents Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tocTitle = document.getElementById('toc-title');
            const tocList = document.getElementById('toc-list');
            const tocArrow = document.getElementById('toc-arrow');

            tocTitle.addEventListener('click', function () {
                tocList.classList.toggle('hidden');
                tocArrow.classList.toggle('rotate-90');
            });
        });
    </script>

@endsection
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
        <link rel="mask-icon" href="{{ asset('/assets/images/site/logo1.jpg') }}" color="#5bbad5">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="author" content="Lee Wisener">
        <meta name="keywords" content="Hosting, Free, cPanel, Wordpress, History">
        <title>{{ $page->title ?? 'StarterHost' }}</title>
        <meta name="description" content="{{ $page->summary ?? 'Free web hosting offers with a particular focus on those wishing to host sites with a historical theme' }}">

        
        @isset($page)
        <!-- Twitter Meta -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:site" content="@wisenerl" />
        <meta name="twitter:title" content="{{ $page->title }}" />
        <meta name="twitter:description" content="{{ $page->pagesummary }}" />
        <meta name="twitter:image" content="{{ asset($page->small_image) }}" />
    
        <!-- Open Graph Meta -->
        <meta property="og:type" content="article" />
        <meta property="og:title" content="{{ $page->title }}" />
        <meta property="og:description" content="{{ $page->pagesummary }}" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:image" content="{{ asset($page->small_image) }}" />
        @endisset

        <!-- moved this to the top due to FOUC -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Venobox css -->
        <link rel="stylesheet" href="{{ asset('assets/css/venobox.min.css') }}" type="text/css" media="screen" />
        <script type="text/javascript" src="{{ asset('assets/js/venobox.min.js') }}"></script>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('/assets/images/site/auschwitz-gate.jpg') }}">

        <!-- FontAwesome -->
        <script src="https://kit.fontawesome.com/0ff5084395.js" crossorigin="anonymous"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    </head>
    <body class="bg-zinc-50">

        <!-- Open Outer Container -->
        <div class="max-w-screen-xl mx-auto bg-white shadow-sm px-4 sm:px-10 lg:px-20 min-h-screen flex flex-col">

            <!--  Yield Navigation -->
            <div>
                @include('partials.navigation')
            </div>
            
            <!-- Content Section -->
            <div class="flex-grow my-10">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="border-t border-zinc-200">
                <div class="text-sm text-center my-6">
                    <p class="text-zinc-400">Copyright 2025, <span class=""><a href="/">StarterHost.uk</a></span></p>
                </div>
            </footer>

        </div>

        <!-- Scripts -->
        <!-- jQuery (Add it before other scripts update to 3.6.4) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
    </body>

</html>

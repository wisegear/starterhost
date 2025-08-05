@extends('layouts.app')

@section('content')

    <!-- Header section -->
    <header>
        <div class="mt-10 md:w-full lg:w-full">
            <h2 class="mt-6 font-bold text-zinc-500 text-3xl md:text-5xl tracking-tight">Welcome to <span class="text-lime-600">StarterHost</span>. The place for Free hosting, mostly everyone welcome.</h2>
            <p class="mt-6 text-zinc-500 text-base">StarterHost offers completely free web hosting, no hidden fees, no subscriptions, and no nonsense. <b>The only catch? None really, just treat us right</b>. 
                Whether you're based in the UK, the EU, or the USA, you're welcome here. There are a few simple rules, which you can read more
                about our hosting in the <span class="text-lime-500 underline"><a href="/about">about</a></span> section. <b>We are big on sites that focus on a historical subject and offer benefits if you 
                    host one.</b></p>
        </div>
    </header>

    <!-- Warning -->
    <div class="mt-10">
        <p class="text-zinc-500 text-center font-bold">Before you rush in, read this <span class="text-lime-600 underline"><a href="/about">page</a></span>, otherwise you will not get very far.</p>
    </div>

    <!-- Image Header -->
    <section class="w-screen relative left-1/2 -translate-x-1/2 mt-20">
        <div class="flex flex-nowrap justify-center gap-16">
            <img src="../assets/images/site/header1.jpg" alt="Gallery image 1" class="rounded-lg w-54 h-52 rotate-5 shrink-0 border border-zinc-400">
            <img src="../assets/images/site/header2.jpg" alt="Gallery image 2" class="rounded-lg w-54 h-52 -rotate-5 shrink-0 border border-zinc-400">
            <img src="../assets/images/site/header3.jpg" alt="Gallery image 3" class="rounded-lg w-54 h-52 rotate-5 shrink-0">
            <img src="../assets/images/site/header4.jpg" alt="Gallery image 4" class="rounded-lg w-54 h-52 -rotate-5 shrink-0 border border-zinc-400">
        </div>
    </section>

    <!-- Plan Section -->
    <div class="my-20 w-10/12 mx-auto">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <div class="text-zinc-700 leading-relaxed">
                <h3 class="text-2xl font-bold mb-4 text-lime-600">Why Choose Us?</h3>
                <p class="mb-4">Because every great project deserves a stable and reliable home. Whether you're preserving family archives, writing about ancient empires, or sharing stories from 
                    the Second World War, your site will thrive here, without ads, fees, or fluff.  Yes, historical sites are a big thing here.</p>
                <p class="mb-4">Our hosting is purpose-built for passionate historians, educators, and those that just want a blog. No distractions, just solid tools and support to get your message out there.</p>
                <p class="mb-4">We currently host sites covering The Holocaust, WWI, WWII, Coding blogs, Shops, Forums and more.</p>
                <p class="mb-4">Want a Blog?  No problem, we have a Wordpress Manager to make life easy.  Want a forum to discuss a hisotrical event?  Install one in a few clicks.  Gallery for images? Covered.
                    Want to build a PHP site from scratch using Laravel?  We can usually accomodate that.
                </p>
                <p class="text-lime-500 font-bold">Hosting a website with a historical theme?  Double the resources listed on the right.</p>
            </div>
            <div class="bg-white border border-lime-100 rounded-lg shadow-lg p-6">
                <h2 class="font-bold text-2xl mb-4 text-lime-600">Totally Free. Seriously.</h2>
                <ul class="space-y-4 text-zinc-600">
                    <li>
                        <span class="font-semibold block">5GB NVMe Storage</span>
                        <span class="text-sm">Super-fast and generous for a personal site</span>
                    </li>
                    <li>
                        <span class="font-semibold block">50GB Monthly Bandwidth</span>
                        <span class="text-sm">Plenty of room for visitors</span>
                    </li>
                    <li>
                        <span class="font-semibold block">cPanel Access</span>
                        <span class="text-sm">Full control, no corners cut</span>
                    </li>
                    <li>
                        <span class="font-semibold block">Global Locations</span>
                        <span class="text-sm">UK, EU & USA, based on availability</span>
                    </li>
                    <li>
                        <span class="font-semibold block">Daily Backups</span>
                        <span class="text-sm">You manage them, you keep them</span>
                    </li>
                    <li>
                        <span class="font-semibold block">WordPress Manager</span>
                        <span class="text-sm">One-click install, effortless updates</span>
                    </li>
                    <li>
                        <span class="font-semibold block">Softaculous Apps Installer</span>
                        <span class="text-sm">Install any of the hundreds of apps available in seconds</span>
                    </li>
                    <li>
                        <span class="font-semibold block">Your site is growing?</span>
                        <span class="text-sm">Talk to us, we can help with more resource for the right sites</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Image Header -->
    <section class="w-screen relative left-1/2 -translate-x-1/2 mt-20">
        <div class="flex flex-nowrap justify-center gap-16">
            <img src="../assets/images/site/image1.png" alt="Gallery image 1" class="rounded-lg w-36 h-32 rotate-5 shrink-0 border border-zinc-400">
            <img src="../assets/images/site/image2.jpg" alt="Gallery image 2" class="rounded-lg w-36 h-32 -rotate-5 shrink-0 border border-zinc-400">
            <img src="../assets/images/site/image3.jpg" alt="Gallery image 3" class="rounded-lg w-36 h-32 rotate-5 shrink-0">
            <img src="../assets/images/site/image4.png" alt="Gallery image 4" class="rounded-lg w-36 h-32 -rotate-5 shrink-0 border border-zinc-400">
        </div>
    </section>

@endsection
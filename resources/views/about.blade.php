@extends('layouts.app')
@section('content')
   <div class="flex flex-col md:flex-row justify-between md:gap-20 my-10 md:my-20">
        <div class="md:w-1/2">
            <!-- Cheating with image -->
            <div class="flex mb-10 md:hidden">
                <img src="../assets/images/site/charity.jpg" class="ml-2 rounded-lg rotate-5 h-[300px] w-[300px]" alt="">
            </div>
            <h2 class="font-bold text-4xl text-zinc-500 md:text-5xl tracking-tight">I’m Lee Wisener. I live in Scotland and I built this site for me as much as you.</h2>
            <div class="wise1text my-10">
                <p>For most of my life I have worked in Banking, I still do. But in the early 2000s I also built up and sold a web hosting business.  So I like to think I know a lot about the industry.</p>
                <p>In 2025 I am no longer involved in the Hosting industry at all but I still needed a place for my own sites, one a personal blog the other a Holocuast history site.</p>
                <p>It got me thinking, why not provide a place for other people who want to host a website, for free.  I needed the hosting anyway so the cost is my burden to bear. The cost is not 
                    that signficant anyway so it made sense and here is the site.</p>
                <p>The rules for our free hosting is really simple.</p>
                <ul class="">
                    <li class="">You must provide a domnain name and use the service to host a website</li>
                    <li class="">All websites must be in the English language</li>
                    <li class="">Legal content only</li>
                    <li class="">No mail spam</li>
                    <li class="">Idle accounts deleted after 30 days</li>
                    <li class="">1 account per member</li>
                    <li class="">Common sense prevails - You never said I couldnt store all my illegally downloaded movies on your server is not a defence</li>
                    <li class="text-orange-500 font-semibold">No users from China or India.  Sorry, you're just a nightmare for abuse</li>
                </ul>
                <p>If you break a rule <b>we do not send any emails, ever,</b> your account is suspended. It is then up to you to log in and create a support ticket for more details. 
                    If you do not your account is deleted after 7 days.  Honestly, if it happens, you know why.</p>
                <p class="font-bold">Scripts run nightly looking for things that should'nt be there.</p>
            </div>
        </div>

            <!-- Side panel -->
        <div class="mt-10 md:w-1/2">
            <div class="hidden md:block">
                <img src="../assets/images/site/charity.jpg" class="rounded-lg rotate-5 md:h-[300px] md:w-[300px] lg:h-[400px] lg:w-[400px] ml-4" alt="">
            </div>

            <!-- Social and Mail -->
            <div class="md:mt-10">
                <a href="https://x.com/wisenerl">
                    <div class="flex gap-4 items-center mb-4">              
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="h-6 w-6 fill-zinc-500 transition group-hover:fill-zinc-600 dark:fill-zinc-400 dark:group-hover:fill-zinc-300">
                        <path d="M13.3174 10.7749L19.1457 4H17.7646L12.7039 9.88256L8.66193 4H4L10.1122 12.8955L4 20H5.38119L10.7254 13.7878L14.994 20H19.656L13.3171 10.7749H13.3174ZM11.4257 12.9738L10.8064 12.0881L5.87886 5.03974H8.00029L11.9769 10.728L12.5962 11.6137L17.7652 19.0075H15.6438L11.4257 12.9742V12.9738Z"></path>
                    </svg> 
                        <p class="text-sm">Follow me on X</p>
                    </div>
                </a>

                <div class="my-10">
                    <h2 class="text-lime-600 font-bold text-2xl mb-4">Important!</h2>
                    <p class="wise1text mb-4 font-semibold">To prevent abuse we regularly check what is being stored on our servers using scripts which flag potentially inappropriate content.  We don't snoop but we don't 
                        just assume on a free service that people will not try and abuse the service.</p>
                    <p class="wise1text mb-4">All you should be hosting here is a website, and that website and all of it's content should be visible to the public.</p>
                    <p class="wise1text mb-4">It's really simple, you be straight with us, we will be straight with you.</p>
                <p class="mb-4">If you're ready to signup, you just need to register and go to the hosting secton once your account is approved.  It's that easy.  The system is quite automated now.</p>
                <p class="text-center font-bold mb-4">By signing up you are agreeing to the rules on this page.</p>
                <div class="flex justify-center">
                    <button class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition">Register New Account</button>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection



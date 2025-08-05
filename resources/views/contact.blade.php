@extends('layouts.app')
@section('content')
   <div class="flex flex-col md:flex-row justify-between md:gap-20 my-10 md:my-20">
        <div class="md:w-1/2">
            <!-- Cheating with image -->
            <div class="flex mb-10 md:hidden">
                <img src="../assets/images/site/support.jpg" class="ml-2 rounded-lg shadow-lg rotate-5 h-[300px] w-[300px]" alt="">
            </div>
            <h2 class="font-bold text-4xl text-zinc-500 md:text-5xl tracking-tight">All contact is now via the support system.  It's easier for us all, no emails.</h2>
            <div class="wise1text my-10">
                <p>I have removed all forms of contact in favour of a new support system.  It's easy to access and raise a ticket.  Just log in, navigaate to Support from the dropdown. From there you
                    can create new tickets or view existing ones.
                </p>
                <div class="flex justify-center">
                   <a href="/login"><button class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition">Login for support</button></a>
                </div>
            </div>
        </div>

            <!-- Side panel -->
        <div class="mt-10 md:w-1/2">
            <div class="hidden md:block">
                <img src="../assets/images/site/support.jpg" class="rounded-lg shadow-lg rotate-5 md:h-[300px] md:w-[300px] lg:h-[400px] lg:w-[400px] ml-4" alt="">
            </div>

            <!-- Social and Mail -->
            <div class="md:mt-10">
                <p class="wise1text">For all other queries from external parties related to abuse queries or other matters.  Please contact <span class="text-lime-700">leejwisener@gmail.com</span>.  Please do not use this email for account,
                    support or other queries, they will be ignored.
                </p>
            </div>
        </div>
    </div>
@endsection
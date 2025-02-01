@extends('layouts.app')
@section('content')
    <!-- Contact Page Header Image -->
    <div class="relative flex flex-col justify-center items-center h-[150px] border mb-10 bg-gray-100 shadow-lg">
        <i class="fa-solid fa-envelope-circle-check fa-6x fa-rotate-by text-slate-200 absolute left-5" style="--fa-rotate-angle: 25deg;"></i>
        <h2 class="font-bold text-center text-2xl z-10">Get in touch</h2>
        <p class="text-center text-gray-500 z-10">If for any reason you want to get in touch, here is how to do it.</p>
        <i class="fa-solid fa-envelope-circle-check fa-6x fa-rotate-by text-slate-200 absolute right-5" style="--fa-rotate-angle: -25deg;"></i>
    </div>
    <!-- Contact Page Text -->
    <div class="mt-4 dark:text-white">
        <h2 class="font-bold text-2xl mb-4">Ways to get in touch</h2> 
        <p class="mb-10">Whilst I am always happy to chat to others who have an interest in the Holocaust I would appreciate it that these contact methods
            are used for that reason only.  It saves me having to delete lots of spam!  
        </p>
    </div>
    <div class="flex flex-col space-y-10 md:flex-row md:justify-center md:space-y-0 md:space-x-10">
        <div class="border rounded shadow-lg p-4 md:w-3/12 shrink-0">
            <h3 class="font-bold text-lg pb-2"><i class="fa-solid fa-envelope text-slate-400 pr-2"></i> Email</h3>
            <p>You can email me directly using the email address below.  It would be appreciated if that is not used to sell me anything, 
                honestly I don't need it :)</p>
            <p class="text-slate-500 pt-2">leejwisener@gmail.com</p>
        </div>
        <div class="border rounded shadow-lg p-4 md:w-3/12 shrink-0">
            <h3 class="font-bold text-lg pb-2"><i class="fa-solid fa-headset text-slate-400 pr-2"></i> Support System</h3>
            <p>As part of this new site I have built a new support system for registered users.  If you are registered then use 
                the dropdown in the menu above once logged in.  To the right you will see your username, in that menu is a link to 
                the supprt system where you can send me a message. </p>
        </div>
        <div class="border rounded shadow-lg p-4 md:w-3/12 shrink-0">
            <h3 class="font-bold text-lg pb-2"><i class="fa-solid fa-hashtag text-slate-400 pr-2"></i> Social Media</h3>
            <p>I have a couple of social media accounts, links below.  Whilst these do not contain abny holocaust related material (yet anyway)
               You can still use them to get in touch. </p>
               <div class="flex justify-center space-x-4 mt-6">
                <a href="https://facebook.com/lee.wisener"><i class="fa-brands fa-facebook-f border shadow-lg p-2 hover:bg-slate-200"></i>
                    <a href="https://x.com/wisenerl"><i class="fa-brands fa-x-twitter border shadow-lg p-2 hover:bg-slate-200"></i>
               </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('content')

    <!-- Viewing the user profile -->
    <div class="w-3/4 mx-auto">
        <!-- Display name and social profiles -->
        <div class="text-center mb-4">
            <p class="font-semibold mb-2 text-lg dark:text-white">You're viewing the user profile for {{ $user->name }}</p>
            <div class="flex space-x-4 justify-center">
                <a href="{{ $user->x }}" class="border border-gray-500 px-2 py-1 rounded-full hover:bg-lime-100"><i class="fa-brands fa-x-twitter w-4 h-4 dark:text-white"></i></a>					
                <a href="{{ $user->facebook }}" class="border border-gray-500 px-2 py-1 rounded-full hover:bg-lime-100"><i class="fa-brands fa-facebook-f text-[#1877f2] w-4 h-4"></i></a>					
                <a href="{{ $user->linkedin }}" class="border border-gray-500 px-2 py-1 rounded-full hover:bg-lime-100"><i class="fa-brands fa-linkedin-in text-[#0a66c2] w-4 h-4"></i></a>
            </div>				
        </div>
        <!-- Display profile and other user details -->
        <div class="flex justify-evenly items-center my-10 dark:text-white">
            <!-- Profile Avatar -->
            <div>
                <img src="{{ asset("/assets/images/avatars/$user->avatar") }}" class="shadow-md border dark:border-gray-400 p-1 rounded">
            </div>
            <div>
                <ul class="text-sm space-y-1 flex flex-col justify-center">
                    <!-- Website -->
                    <li>
                        <i class="fa-brands fa-internet-explorer mr-2"></i>				
                        <a href="{{ $user->website }}">{{ $user->website }}</a>
                    </li>       
                    <!-- Location -->            
                    <li>
                        <i class="fa-solid fa-globe mr-2"></i>				
                        {{ $user->location }}
                    </li>
                    <!-- Email -->
                    <li>
                        <i class="fa-solid fa-at mr-2"></i>	
                        <!-- is email shared publically -->
                        @if($user->email_visible === 0)
                            Not shared
                        @else				
                        {{ $user->email }}
                        @endif
                    </li>
                </ul>
            </div>
        </div>
        <!-- User Bio -->
        <div class="text-center bg-lime-100 dark:bg-gray-200">
            @if (empty($user->bio))
                <!-- If no user Bio -->
                <p class="border p-2">User has not provided any information about themselves.</p>
                @else
                    <!-- display user Bio -->
                    <p class="border rounded-md p-2 text-gray-700 text-sm">{{ $user->bio }}</p>
                @endif
        </div>
            <!-- Edit Profile -->
            @if (Auth::user()->name === $user->name || Auth::user()->has_user_role('Admin'))
                <div class="my-6 text-center">
                    <a class="border dark:border-gray-400 rounded py-2 px-2 text-sm bg-lime-500 hover:bg-lime-400" href="/profile/{{ $user->name }}/edit" role="button">Edit Profile</a>
                </div>
            @endif
    </div>

@endsection
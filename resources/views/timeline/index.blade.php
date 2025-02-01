@extends('layouts.app')

@section('content')

    <div class="relative flex flex-col justify-center items-center h-[150px] border mb-10 bg-gray-100 shadow-lg">
        <i class="fa-solid fa-timeline fa-6x fa-rotate-by text-slate-200 absolute left-5" style="--fa-rotate-angle: 25deg;"></i>
        <h2 class="font-bold text-center text-2xl z-10">A Holocaust Timeline</h2>
        <p class="text-center text-gray-500 z-10">Explore the events as they unfolded from the early 30s through to the mid 40s</p>
        <i class="fa-solid fa-timeline fa-6x fa-rotate-by text-slate-200 absolute right-5" style="--fa-rotate-angle: -25deg;"></i>
    </div>

    @can('Admin')
    <div class="text-center">
        <a href="/timeline/create"><button class="wise-button-md">Create Event</button></a>
    </div>
    @endcan

    <div class="text-center my-10">
        <h2 class="text-lg mb-4 font-bold">Select a specific year or search for something more specific.</h2>
    </div>

    <div class="flex flex-wrap justify-center gap-4">
        
        <a href="/timeline?year=earlier">
            <button class="wise-button-sm-gray">Earlier</button>
        </a>
        
        
        @foreach ($years as $year)
            <a href="/timeline?year={{ $year }}">
                <button class="wise-button-sm"> {{ $year }}</button>
            </a>
        @endforeach
        
        <a href="/timeline?year=later">
            <button class="wise-button-sm-gray">LAter</button>
        </a>

    </div>

    <div class="flex justify-center my-10">
        <form method="get" action="/timeline" class="mb-5 w-11/12 md:w-1/3">
            <div class="relative">
                <input type="text" class="border border-gray-300 rounded-md w-full text-sm pl-2 pr-10" id="search" name="search" placeholder="Search events instead of years">
                <button class=" absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>
    </div>

    <div class="container mx-auto p-4 relative mb-10">
        <!-- Vertical line in center (shown on md+ screens) -->
        <div class="hidden md:block absolute border-l-2 border-gray-200 left-1/2 transform -translate-x-1/2 h-full"></div>
        
        @foreach ($events as $index => $event)
            <!-- Parent wrapper for each event -->
            <div 
                class="mb-8 flex flex-col md:flex-row 
                       {{ $loop->iteration % 2 == 0 ? 'md:justify-start' : 'md:justify-end' }}"
            >
                <!-- Content wrapper (on md, half width) -->
                <div class="md:w-1/2 md:px-4 relative">
                    
                    <!-- The “dot” on the timeline, only visible on md+ -->
                    <div 
                        class="hidden md:block absolute w-4 h-4 bg-slate-500 rounded-full top-2 
                               {{ $loop->iteration % 2 == 0 ? '-left-2' : '-right-2' }} 
                               border-2 border-white"
                    ></div>
                    
                    <!-- Timeline Item Card -->
                    <div class="bg-white p-4 border rounded-md shadow-lg">
                        <!-- Date -->
                        <div class="flex justify-between">
                            <h2 class="text-sm font-bold text-slate-500">
                                {{ \Carbon\Carbon::parse($event->date)->format('jS M Y') }}
                            </h2>
                            <!-- Admin buttons -->
                            @can('Admin')
                                <div class="flex space-x-3 justify-end">
                                    <form 
                                        action="/timeline/{{ $event->id }}" 
                                        method="POST" 
                                        onsubmit="return confirm('Do you really want to delete this event?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="text-slate-500 hover:text-red-600"
                                        >
                                            <i class="fa-solid fa-trash-can fa-xs"></i>
                                        </button>
                                    </form>
                                    <a 
                                        href="/timeline/{{ $event->id }}/edit"
                                        class="text-slate-500 hover:text-yellow-600"
                                    >
                                        <i class="fa-solid fa-pen-to-square fa-xs"></i>
                                    </a>
                                </div>
                            @endcan
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-lg font-bold mt-1 mb-2">
                            {{ $event->title }}
                        </h3>
                        
                        <!-- Text -->
                        <p class="text-sm text-gray-700 mb-2">
                            {{ $event->text }}
                        </p>
                    </div>
                    <!-- End Timeline Item Card -->
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-center">
        <div class="w-1/2">
            {{ $events->links() }}
        </div>
    </div>

@endsection
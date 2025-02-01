@extends('layouts.app')

@section('content')

    <div class="relative flex flex-col justify-center items-center h-[150px] border mb-10 bg-gray-100 shadow-lg">
        <i class="fa-solid fa-quote-left fa-10x fa-rotate-by text-slate-200 absolute left-5" style="--fa-rotate-angle: 25deg;"></i>
        <h2 class="font-bold text-center text-2xl z-10">Search quotes related to the Holocaust</h2>
        <p class="text-center text-gray-500 z-10">Search through many quotes from survivors, books, tv and more</p>
        <i class="fa-solid fa-quote-right fa-10x fa-rotate-by text-slate-200 absolute right-5" style="--fa-rotate-angle: 25deg;"></i>
    </div>

    <div class="flex flex-col md:flex-row md:space-x-10">
        <div class="">
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach ($quotes as $quote)
                    <div class="flex flex-col justify-between border shadow-lg p-4">
                        <p class="text-gray-700">{{ $quote->text }}</p>
                       <p class="font-bold pt-4 text-sm text-slate-500 text-right"> <a href="/quotes?author={{ Str::slug($quote->author) }}">- {{ $quote->author }}</a></p>
                        @can('Admin')
                        <div class="flex justify-end space-x-2">
                            <form action="/quotes/{{ $quote->id }}" method="POST" onsubmit="return confirm('Do you really want to delete this Quote?');">
                            {{ csrf_field() }}
                            {{ method_field ('DELETE') }} 
                            <button class="hover:text-red-500" role="button" type="submit">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                            </form>
                            <a class="hover:text-yellow-500" href="/quotes/{{ $quote->id }}/edit" role="button"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        </div>
                        @endcan
                    </div>
                @endforeach
            </div>
            <div class="mt-10 w-2/3 mx-auto">
                {{ $quotes->links() }}
            </div>
        </div>
        <div class="mt-10 md:mt-0 md:w-3/12 md:shrink-0">
            <!-- Find quotes based on user search input -->
            <div class="mb-10">
                <form method="get" action="/quotes" class="mb-5">
                    <h2 class="text-lg font-bold mb-2">Search Quotes</h2>
                    <div class="relative">
                        <input type="text" class="border border-gray-300 rounded-md w-full text-sm pl-2 pr-10" id="search" name="search" placeholder="Enter search term">
                        <button class=" absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>
            <!-- List the top x authors -->
            <div class="mb-10">
                <h2 class="text-lg font-bold mb-4 border-b">Most Quoted Authors</h2>
                @foreach ($top_authors as $author)
                    <p class="mb-2"><a href="/quotes?author={{ Str::slug($author) }}">{{ $author }}</a></p>
                @endforeach
            </div>
            <!-- View Admin for quotes -->
            @can('Admin')
                <div class="mb-10">
                    <h2 class="text-lg font-bold mb-4 text-red-800 border-b"><i class="fa-solid fa-user-secret"></i> Admin</h2>
                    <div class="flex justify-center mb-10">
                        <a href="/quotes/create"><button class="wise-button-md">Create New Quote</button></a>
                    </div>
                    @foreach ($unpublished as $item)
                        <div class="flex justify-between mb-2">
                            <div>
                                <a class="text-slate-500" href="/quotes/{{ $item->id }}/edit">{{ $item->author }}</a>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <form action="/quotes/{{ $item->id }}" method="POST" onsubmit="return confirm('Do you really want to delete this Quote?');">
                                {{ csrf_field() }}
                                {{ method_field ('DELETE') }} 
                                <button class="hover:text-red-500" role="button" type="submit">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                </form>
                                <a class="hover:text-yellow-500" href="/quotes/{{ $item->id }}/edit" role="button"><i class="fa-solid fa-pen-to-square"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endcan
        </div>
    </div>

@endsection
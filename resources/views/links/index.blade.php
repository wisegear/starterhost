@extends('layouts.app')
@section('content')

    <div class="relative flex flex-col justify-center items-center h-[150px] border mb-10 bg-gray-100 shadow-lg">
        <i class="fa-solid fa-link fa-6x fa-rotate-by text-slate-200 absolute left-5" style="--fa-rotate-angle: 25deg;"></i>
        <h2 class="font-bold text-center text-2xl z-10">Links Section</h2>
        <p class="text-center text-gray-500 z-10 w-2/3">This section contains useful links to various sites that provide infomration, tools, calculators and so on related to mortgages that I have checked and verified as safe and correct.</p>
        <i class="fa-solid fa-link fa-6x fa-rotate-by text-slate-200 absolute right-5" style="--fa-rotate-angle: 25deg;"></i>
    </div>

    <!-- List of links and category menu -->
    <div class="flex flex-col-reverse md:flex md:flex-row md:space-x-10 mt-10">
        <div class="md:w-9/12">
            <div class="space-y-10">
                @foreach ($links as $link)
                    <div class="flex items-center space-x-10 border dark:border-gray-600 rounded p-4 shadow-lg">
                        <img class="rounded border dark:border-gray-600 max-h-[150px]" src="{{ asset('storage/images/links/' . $link->image) }}" alt="">
                        <div class="flex flex-col w-full">
                            <a href="{{ $link->url }}"><h2 class="font-bold text-lg dark:text-white">{{ $link->title }}</h2></a>
                            <div class="dark:text-white text-gray-500">{!! $link->description !!}</div>
                            <a href="{{ $link->url }}">
                                <button class="self-start inline-block wise-button-sm mt-2">
                                    Visit Website
                                </button>
                            </a>
                            @can('Admin')
                            <div class="flex justify-end space-x-2">
                                <form action="/links/{{ $link->id }}" method="POST" onsubmit="return confirm('Do you really want to delete this Link?');">
                                {{ csrf_field() }}
                                {{ method_field ('DELETE') }} 
                                <button class="hover:text-red-500 dark:text-white" role="button" type="submit">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                                </form>
                                <a class="hover:text-yellow-500 dark:text-pink-500" href="/links/{{ $link->id }}/edit" role="button"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                            </div>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <div class="w-3/4 mx-auto mt-10">
                {{ $links->links() }}
            </div>
        </div>
        <div class="md:w-3/12 mb-10">
            <div>
                <h2 class="font-bold text-xl border-b dark:border-gray-600 mb-2 dark:text-white">Categories</h2>
                <ul class=" dark:text-white">
                    @foreach ($categories as $category)
                        <li><a href="/links?category={{ $category->name }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            @can('Admin')
            <!-- Admin -->
            <div class="hidden md:block my-6">
             <h2 class="text-xl font-bold text-gray-700 dark:text-red-300 border-b dark:border-b-gray-700 border-gray-300 mb-4"><i class="fa-solid fa-user-secret text-red-800"></i> Admin Tools</h2>
                <div class="flex justify-center">
                    <p class="wise-button-md"><a href="/links/create">Create New Link</a></p>
                </div>
                <div class="flex flex-col space-y-2 mt-4">
                    @foreach ($unpublished as $item)
                        <div class="flex justify-between mb-2">
                            <div>
                                <a class="text-slate-500" href="/links/{{ $item->id }}/edit">{{ $item->title }}</a>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <form action="/links/{{ $item->id }}" method="POST" onsubmit="return confirm('Do you really want to delete this link?');">
                                {{ csrf_field() }}
                                {{ method_field ('DELETE') }} 
                                <button class="hover:text-red-500" role="button" type="submit">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                </form>
                                <a class="hover:text-yellow-500" href="/links/{{ $item->id }}/edit" role="button"><i class="fa-solid fa-pen-to-square"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div> 
           @endcan 
        </div>

    </div>

@endsection
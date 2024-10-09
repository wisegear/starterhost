@extends('layouts.app')

@section('content')

    <div>
        <h2 class="text-2xl font-bold text-center">Edit Quote</h2>
        <p class="text-center text-gray-500">Use this form to edit an existing quote.</p>
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4 my-10">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="my-10">
        <form action="/quotes/{{ $quote->id }}" method="POST">
        @csrf
        {{ method_field('PUT') }}
            <div class="flex flex-col w-1/2">
                <label for="author" class="font-bold mb-2">Quote Author</label>
                @error('quote_author')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                <input class="rounded-lg" type="text" id="quote_author" name="quote_author" value="{{ $quote->author }}"> 
            </div>
            <div class="flex flex-col w-1/2 my-10">
                <label for="text" class="font-bold mb-2">Quote Text</label>
                @error('quote_text')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                <textarea class="rounded-lg" type="text" id="quote_text" name="quote_text">{{ $quote->text }}</textarea> 
            </div>
            <div class="flex space-x-10 items-center mb-10">
                <label for="published">Published?</label>
                <input type="checkbox" name="published" id="published" class="rounded-lg" checked>
            </div>
            <div>
                <button type="submit" class="border p-2 rounded-lg bg-slate-600 text-white font-bold text-gray-600">Update Quote</button>
            </div>
        </form>
    </div>

@endsection
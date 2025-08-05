@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-zinc-500 text-center mb-4">Request a New Hosting Account</h1>
    <p class="text-center text-zinc-600 mb-4">Hosting locations are dependent on stock availability.  Whatever locations we have in the dropdown below are your choices.  We cannot accept requests for specific locations.</p>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('hosting.store') }}" method="POST" class="bg-white px-8 pt-6 pb-8 mb-4">
        @csrf

        <!-- Server Selection Dropdown -->
        <div class="mb-4">
            <label for="server_id" class="block text-gray-700 text-sm font-bold mb-2">
                Server Location
            </label>
            <select name="server_id" id="server_id" class="block w-full bg-white border border-zinc-300 p-2 rounded shadow focus:outline-none focus:shadow-outline" required>
                <option value="">Select a server location</option>
                @foreach($servers as $server)
                    <option value="{{ $server->id }}">{{ $server->location }}</option>
                @endforeach
            </select>
        </div>

        <!-- Domain Input -->
        <div class="mb-4">
            <label for="domain" class="block text-gray-700 text-sm font-bold mb-2">
                Domain Name
            </label>
            <input type="text" name="domain" id="domain" placeholder="example.com"
                class="shadow appearance-none border border-zinc-300 rounded w-full p-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <!-- Country Input -->
        <div class="mb-4">
            <label for="country" class="block text-gray-700 text-sm font-bold mb-2">
                Your Country
            </label>
            <input type="text" name="country" id="country" placeholder="Your Country"
                class="shadow appearance-none border border-zinc-300 rounded w-full p-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <!-- Reason Textarea -->
        <div class="mb-4">
            <label for="reason" class="block text-gray-700 text-sm font-bold mb-2">
                Reason for Hosting Request
            </label>
            <textarea name="reason" id="reason" rows="4" placeholder="Explain why you want hosting"
                class="shadow appearance-none border border-zinc-300 rounded w-full p-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-between">
            <button type="submit"
                class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition">
                Submit Request
            </button>
        </div>
    </form>
</div>
@endsection
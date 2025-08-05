@extends('layouts.admin')

@section('content')

    <div class="text-center mb-10">
        <h2 class="font-bold text-2xl">Create a new server</h2>
        <p>Use this form to create a new server, all fields are required</p>
    </div>

    <!-- Display any validation errors -->
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

    <form action="{{ route('servers.store') }}" method="POST" class="max-w-3xl mx-auto p-6">
        @csrf
        <div class="mb-4">
            <label for="provider" class="block text-gray-700 font-bold mb-2">Provider</label>
            <input type="text" id="provider" name="provider" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="location" class="block text-gray-700 font-bold mb-2">Location</label>
            <input type="text" id="location" name="location" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="cpanelUrl" class="block text-gray-700 font-bold mb-2">cPanel URL</label>
            <input type="text" id="cpanelUrl" name="cpanelUrl" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="whmUrl" class="block text-gray-700 font-bold mb-2">WHM URL</label>
            <input type="text" id="whmUrl" name="whmUrl" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="apiKey" class="block text-gray-700 font-bold mb-2">API Key</label>
            <input type="text" id="apiKey" name="apiKey" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="ns1" class="block text-gray-700 font-bold mb-2">Username</label>
            <input type="text" id="username" name="username" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="ns1" class="block text-gray-700 font-bold mb-2">Package Name</label>
            <input type="text" id="package" name="package" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="ns1" class="block text-gray-700 font-bold mb-2">NS1</label>
            <input type="text" id="ns1" name="ns1" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="ns2" class="block text-gray-700 font-bold mb-2">NS2</label>
            <input type="text" id="ns2" name="ns2" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="ip" class="block text-gray-700 font-bold mb-2">IP Address</label>
            <input type="text" id="ip" name="ip" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="">
            <button type="submit" class="px-4 py-2 bg-lime-500 rounded hover:bg-blue-600">
                Create Server
            </button>
        </div>
    </form>

@endsection
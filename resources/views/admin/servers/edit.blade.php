@extends('layouts.admin')

@section('content')

    <div class="text-center mb-10">
        <h2 class="font-bold text-2xl">Edit existing server</h2>
        <p>Use this form to edit an existing server to make changes</p>
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

    <form action="{{ route('servers.update', $server->id) }}" method="POST" class="max-w-3xl mx-auto p-6">
        @csrf
        @method('PUT')

        <!-- Provider Field -->
        <div class="mb-4">
            <label for="provider" class="block text-gray-700 font-bold mb-2">Provider</label>
            <input 
                type="text" 
                id="provider" 
                name="provider" 
                value="{{ old('provider', $server->provider) }}" 
                required 
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
            >
        </div>

        <!-- Location Field -->
        <div class="mb-4">
            <label for="location" class="block text-gray-700 font-bold mb-2">Location</label>
            <input 
                type="text" 
                id="location" 
                name="location" 
                value="{{ old('location', $server->location) }}" 
                required 
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
            >
        </div>

        <!-- cPanel URL Field -->
        <div class="mb-4">
            <label for="cpanelUrl" class="block text-gray-700 font-bold mb-2">cPanel URL</label>
            <input 
                type="text" 
                id="cpanelUrl" 
                name="cpanelUrl" 
                value="{{ old('cpanelUrl', $server->cpanelUrl) }}" 
                required 
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
            >
        </div>

        <!-- WHM URL Field -->
        <div class="mb-4">
            <label for="whmUrl" class="block text-gray-700 font-bold mb-2">WHM URL</label>
            <input 
                type="text" 
                id="whmUrl" 
                name="whmUrl" 
                value="{{ old('whmUrl', $server->whmUrl) }}" 
                required 
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
            >
        </div>

        <!-- API Key Field -->
        <div class="mb-4">
            <label for="apiKey" class="block text-gray-700 font-bold mb-2">API Key</label>
            <input 
                type="text" 
                id="apiKey" 
                name="apiKey" 
                value="{{ old('apiKey', $server->apiKey) }}" 
                required 
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
            >
        </div>

        <!-- cPanel username (admin) Field -->
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                value="{{ old('ns1', $server->username) }}" 
                required 
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
            >
        </div>

        <!-- Package Field -->
        <div class="mb-4">
            <label for="package" class="block text-gray-700 font-bold mb-2">Package Name</label>
            <input 
                type="text" 
                id="package" 
                name="package" 
                value="{{ old('ns1', $server->package) }}" 
                required 
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
            >
        </div>

        <!-- NS1 Field -->
        <div class="mb-4">
            <label for="ns1" class="block text-gray-700 font-bold mb-2">NS1</label>
            <input 
                type="text" 
                id="ns1" 
                name="ns1" 
                value="{{ old('ns1', $server->ns1) }}" 
                required 
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
            >
        </div>

        <!-- NS2 Field -->
        <div class="mb-4">
            <label for="ns2" class="block text-gray-700 font-bold mb-2">NS2</label>
            <input 
                type="text" 
                id="ns2" 
                name="ns2" 
                value="{{ old('ns2', $server->ns2) }}" 
                required 
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
            >
        </div>

        <!-- IP Field -->
        <div class="mb-4">
            <label for="ip" class="block text-gray-700 font-bold mb-2">IP Address</label>
            <input 
                type="text" 
                id="ip" 
                name="ip" 
                value="{{ old('ip', $server->ip) }}" 
                required 
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
            >
        </div>

        <!-- Submit Button -->
        <div class="">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Update Server
            </button>
        </div>
    </form>

@endsection
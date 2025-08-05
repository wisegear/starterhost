@extends('layouts.admin')

@section('content')

    <!-- Header section -->
    <div class="text-center mb-10">
        <h2 class="text-2xl font-bold">Server Management</h2>
        <p>Use this area to manage the server details of all servers being used to provide hosting services</p>
        <a href="/admin/servers/create" class=""><button class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition inline-block mx-auto my-6">Create New Server</button></a>
    </div>

    <!-- Display any messages passed from the controller -->
    @if (session('success'))
        <div class="bg-lime-500 text-white p-4 rounded mb-4 my-10 mx-auto">
            {{ session('success') }}
        </div>
    @endif


    <!--  Server list -->
    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <tr class="bg-gray-100">
                <th>ID</th>
                <th>Provider</th>
                <th>Location</th>
                <th>cPanel URL</th>
                <th>WHM URL</th>
                <th>API Key</th>
                <th>Username</th>
                <th>Package</th>
                <th>IP Address</th>
                <th>NS</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            @foreach($servers as $server)
                <tr class="text-xs">
                    <td>{{ $server->id }}</td>
                    <td>{{ $server->provider }}</td>
                    <td>{{ $server->location }}</td>
                    <td><a href="{{ $server->cpanelUrl }}" class="underline text-lime-700">{{ $server->cpanelUrl }}</a></td>
                    <td><a href="{{ $server->whmUrl }}" class="underline text-lime-700">{{ $server->whmUrl }}</a></td>
                    <td>{{ $server->apiKey }}</td>
                    <td>{{ $server->username }}</td>
                    <td>{{ $server->package }}</td>
                    <td>{{ $server->ip }}</td>
                    <td>{{ $server->ns1 }} {{ $server->ns2 }}</td>
                    <td><a href="/admin/servers/{{ $server->id}}/edit"><button class="border rounded p-1 uppercase bg-orange-200">Edit</button></a></td>
                    <td class="flex justify-center">
                        <form action="{{ route('servers.destroy', $server->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                                @if($server->hosting->count() > 0)
                                    <button type="submit" class="border rounded p-2 uppercase bg-red-300" disabled>
                                        {{ $server->hosting->count() }}
                                    </button>
                                @else
                                    <button type="submit"
                                            class="border rounded p-1 uppercase bg-red-300"
                                            onclick="return confirm('Are you sure you want to delete the server?');">
                                        Delete
                                    </button>
                                @endif
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

@endsection
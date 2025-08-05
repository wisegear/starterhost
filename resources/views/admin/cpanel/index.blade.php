@extends('layouts.admin')

@section('content')

    <div class="flex flex-col rounded items-center border-b pb-4">
        <h2 class="font-bold text-2xl">cPanel Management</h2>
        <p>View all current hosting accounts listed in the server.</p>
    </div>

    <div class="my-10">

        <div class="mb-10">
            <h3 class="mb-2 font-bold">Pending Requests:</h3>
            <table class="table w-full text-sm">
                <thead>
                    <tr class="bg-slate-200">
                        <th>User ID</th>
                        <th>Server ID</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Reason</th>
                        <th>Domain</th>
                        <th>Create</th>
                        <th>Delete</th>
                        <th>Ban User</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pending as $item)
                        <tr>
                            <td class="">{{ $item->user->id }}</td>
                            <td class="">{{ $item->servers->id }}</td>
                            <td><a href="../profile/{{$item->user->name_slug}}" class="underline text-lime-700">{{ $item->user->name }}</a></td>
                            <td>{{ $item->country }}</td>
                            <td>{{ $item->reason }}</td>
                            <td class="underline"><a href="http://{{ $item->domain }}" target="_blank">{{ $item->domain }}</a></td>
                            
                            <!-- Create button with form to pass data to the cPanel API -->
                            <td class="text-center">
                                <form action="{{ route('admin.cpanel.create') }}" method="POST" onsubmit="return confirm('Are you sure you want to accept this hosting request?');">
                                    @csrf
                                    <input type="hidden" name="username" value="{{ strtolower(Str::limit(preg_replace('/[^a-zA-Z0-9]/', '', $item->user->name), 8, '')) }}">
                                    <input type="hidden" name="domain" value="{{ $item->domain }}">
                                    <input type="hidden" name="password" value="{{ Str::random(12) }}">
                                    <input type="hidden" name="contact_email" value="{{ $item->user->email }}">
                                    <input type="hidden" name="user_id" value="{{ $item->user->id }}">
                                    <input type="hidden" name="server_id" value="{{ $item->server_id }}">
                                    <button type="submit" class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition">Create</button>
                                </form>
                            </td>

                            <!-- Reject users request but don't prevent a new request -->
                            <td class="text-center">
                                <form action="{{ route('admin.cpanel.reject') }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this hosting request?');">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $item->user->id }}">
                                    <button type="submit" class="cursor-pointer bg-orange-500 hover:bg-orange-400 text-white text-sm font-medium p-2 rounded-md transition">Reject</button>
                                </form>
                            </td>            
                            
                            <!-- Ban user at user level and delete hosting request -->
                            <td class="text-center">
                                <form action="{{ route('admin.cpanel.ban') }}" method="POST" onsubmit="return confirm('Are you sure you want to ban this user?');">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $item->user->id }}">
                                    <button type="submit" class="cursor-pointer bg-red-500 hover:bg-red-400 text-white text-sm font-medium p-2 rounded-md transition">Ban User</button>
                                </form>
                            </td> 

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h3 class="mb-2 font-bold">cPanel User Accounts With Hosting:</h3>
        <table class="table w-full">
            <thead>
                <tr class="bg-slate-200">
                    <th>Server ID</th>
                    <th>Member Name</th>
                    <th>cPanel User</th>
                    <th>Domain</th>
                    <th>Email</th>
                    <th>Disk Usage</th>
                    <th>Traffic usage</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <!-- Using this to get the server ID from the hosting table so its easier to identify where each account is hosted -->
                    @php
                        $hosting = \App\Models\Hosting::where('username', $account['user'])->first();
                        $user = $hosting?->user;
                    @endphp
                        <td>{{ $hosting?->server_id }}</td>
                        <td><a class="underline text-green-600 hover:text-green-400" href="../profile/{{ $user?->name_slug }}"> {{ $user?->name ?? 'N/A' }} </a></td>
                        <td>{{ $account['user'] }}</td>
                        <td class="underline"><a href="http://{{ $account['domain'] }}" target="_blank">{{ $account['domain'] }}</a></td>
                        <td>{{ $account['email'] }}</td>
                        <td>{{ $account['diskused'] }}</td>
                        <td>{{ $account['bandwidth'] ?? 'N/A' }}</td>
                        <td class="text-center">
                            <div class="flex justify-center space-x-4">
                                @if( $account['suspended'] == true )
                                    <form action="{{ route('admin.cpanel.unsuspend') }}" method="POST" onsubmit="return confirm('Are you sure you want to unsuspend this user?');">
                                        @csrf
                                        <input type="hidden" name="user_account" value="{{ $account['user'] }}">
                                        <input type="hidden" name="server_id" value="{{ $hosting?->server_id }}">
                                        <button type="submit" class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition">Resume</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.cpanel.suspend') }}" method="POST" onsubmit="return confirm('Are you sure you want to ban this user?');">
                                        @csrf
                                        <input type="hidden" name="user_account" value="{{ $account['user'] }}">
                                        <input type="hidden" name="server_id" value="{{ $hosting?->server_id }}">
                                        <button type="submit" class="cursor-pointer bg-orange-500 hover:bg-orange-400 text-white text-sm font-medium p-2 rounded-md transition">Suspend</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.cpanel.delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to ban this user?');">
                                    @csrf
                                    <input type="hidden" name="user_account" value="{{ $account['user'] }}">
                                    <input type="hidden" name="server_id" value="{{ $hosting?->server_id }}">
                                    <button type="submit" class="cursor-pointer bg-red-500 hover:bg-red-400 text-white text-sm font-medium p-2 rounded-md transition">Delete</button>
                                </form>
                            </div>
                        </td> 
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection
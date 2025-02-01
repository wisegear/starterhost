@extends('layouts.admin')

@section('content')

    <div class="flex flex-col rounded items-center bg-lime-300 p-4">
        <h2 class="font-bold text-2xl">Timeline Management</h2>
        <p>Create new and manage existing timeline events</p>
    </div>

    <!-- Button to create new article -->  
    <div class="my-10 text-center space-x-10">
        <a href="/timeline/create"><button class="border rounded p-2 uppercase font-bold bg-lime-500 hover:bg-lime-300 text-xs">Create New Event</button></a>
    </div>

    <!-- List existing articles -->
    <div class="">
        <h2 class="font-bold text-lg">Existing Timeline Events:</h2>
        <table class="my-4 w-full">
            <tr class="bg-lime-500">
                <th>ID</th>
                <th>Title</th>
                <th>date</th>
                <th>Description</th>
                <th>Published?</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            @foreach ($pages as $page)
             
            <tr>
                <td>{{ $page->id }}</td>
                <td>{{ $page->title }}</td>
                <td>{{ $page->date }}</td>
                <td>{{ $page->text }}</td>
                <td>{{ $page->published ? 'Yes' : 'No' }}</td>
                <td><a href="/timeline/{{ $page->id }}/edit"><button class="border p-1 uppercase bg-orange-500 rounded text-xs text-white">Edit</button></a></td>
                <td>
                    <form action="/timeline/{{ $page->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="border p-1 uppercase bg-red-500 rounded text-xs text-white">Delete</button>
                    </form>
                </td>
            </tr>            

            @endforeach
        </table>
        <!-- Paginate -->
        <div>
            {{ $pages->links() }}
        </div>
    </div>

@endsection
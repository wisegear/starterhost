@extends('layouts.admin')
@section('content')   

<div class="mb-10 w-4/5 mx-auto">
	<h1 class="text-xl font-bold text-center">Tickets</h1>
	<p class="text-sm text-gray-500">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nisi vitae debitis velit at repudiandae necessitatibus quis molestiae dolore voluptas saepe exercitationem architecto provident deserunt laborum earum, sapiente ex aspernatur ipsa doloribus iste ullam? Laudantium nemo quas et pariatur ipsam vel.</p>
</div>

<div class="text-center space-x-4 mb-10">
	<a class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition inline-block mx-auto" href="/admin/support?closed=true" role="button">Show Closed</a>
	<a class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition inline-block mx-auto" href="/admin/support" role="button">Show Open</a>
</div>

	<div class="w-4/5 mx-auto text-sm text-center">
        <table class="table-fixed">
            <thead class="">
              <tr class="bg-gray-100">
                <th class="border w-4/12">Title</th>
                <th class="border w-2/12">Opened By</th>
                <th class="border w-2/12">When replied</th>
                <th class="border w-2/12">Last reply by</th>
                <th class="border w-2/12">Replies</th>
                <th class="border w-1/12">Status</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                  <tr>
                    <td class="border text-indigo-500"><a href="/support/{{ $ticket->id }}">{{ $ticket->title }}</a></td>
                    <td class="border">{{ $ticket->users->name }}</td>
                    @if ($ticket->comments && $ticket->comments->isNotEmpty())
                        <td class="border">{{ $ticket->comments->last()->created_at->diffForHumans() }}</td>
                        <td class="border">{{ $ticket->comments->last()->user->name }}</td>
                    @else
                        <td class="border">---</td>
                        <td class="border">---</td>
                    @endif
                    <td class="border">{{ $ticket->comments->count() }}</td>	
                    <td class="border">{{ $ticket->status }}</td>
                  </tr>
              @endforeach
            </tbody>
          </table>
		<div class="w-1/2 mx-auto my-10">
			{{ $tickets->links() }} 
		</div>
	</div>

@endsection
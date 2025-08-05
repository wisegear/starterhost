@extends('layouts.app')
@section('content')

	<div class="">
		<h2 class="text-2xl font-bold text-center mb-5 text-zinc-500">Ticket Details</h2>
	</div>

	<div class="border border-zinc-300 rounded-md shadow-lg p-2 w-4/5 mx-auto mt-10">
		<h2 class="font-bold text-2xl text-center mb-1 dark:text-white">{{ $ticket->title }}</h2>
		<div class="text-center text-gray-700 space-x-4 mb-4 dark:text-white">
			<a href="" class="text-sm">{{ $ticket->users->name }}</a>
			<a href="" class="text-sm">{{ $ticket->created_at->diffForHumans() }}</a>
		</div>
		<p class="text-gray-500 text-center dark:text-white">{!! $ticket->text !!}</p>
	</div>

	<div class="my-10 text-center">
		<form method="POST" action="/support/{{ $ticket->id }}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{ method_field('PUT') }}
			<div class="space-x-4">  
				@if ( $ticket->status === 'Open' || $ticket->status === 'In Progress' || $ticket->status === 'Awaiting Reply' )
					<button type="submit" class="cursor-pointer bg-red-500 hover:bg-red-400 text-white text-sm font-medium p-2 rounded-md transition" name="closeTicket" value="true">Close Ticket</button> 
				@elseif ( $ticket->status == "Closed" || $ticket->status == "In Progress")
					<button type="submit" class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition" name="openTicket" value="true">Open Ticket</button> 
				@endif
				@can('Admin')
					<button type="submit" class="cursor-pointer bg-orange-500 hover:bg-orange-400 text-white text-sm font-medium p-2 rounded-md transition" name="inProgress" value="true">In Progress</button>
					<button type="submit" class="cursor-pointer bg-slate-500 hover:bg-slate-400 text-white text-sm font-medium p-2 rounded-md transition" name="AwaitingReply" value="true">Awaiting Reply</button>
				@endcan
			</div>  
		</form>	
	</div>

<div class="w-4/5 mx-auto">
	<p class="text-xl font-bold text-center my-10 dark:text-white">Ticket Replies</p>
	@foreach ( $ticket->comments as $comment)
		<div class="border border-zinc-300 shadow-lg rounded-md p-2 my-10 dark:text-white bg-gray-100 dark:bg-slate-700">
			<div class="text-center">
				<a href="" class="text-sm mr-5">{{ $comment->user->name }}</a>
				<a href="" class="text-sm">{{ $comment->created_at->diffForHumans() }}</a>
				<p class=" mt-2">{!! $comment->comment_text !!}</p>
			</div>
		</div>
	@endforeach						
</div>
<div class="w-4/5 mx-auto my-10">
	<form method="POST" action="/support/{{ $ticket->id }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	{{ method_field('PUT') }}     
		<div class="text-center mb-4">
			<div class="text-red-500">{{ $errors->has('comment') ? 'You need to tell us something before replying :)' : '' }}</div>
				<textarea class="w-full border rounded border-zinc-300 p-2" name="comment" id="text" placeholder="Reply here."></textarea>
		</div>  
		<button type="submit" class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition" style="">Add Reply</button> 
	</form>			
</div>
@endsection
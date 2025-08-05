@extends('layouts.app')
@section('content')
<div class="text-center w-4/5 mx-auto mb-10">
	<h2 class="text-2xl font-bold mb-2">Creating a New Ticket</h2>
	<p class="text-gray-500">If you have any questions about the service or queries in general please open a ticket rather than sending an email.  This is allows me to manage all user requests quicker and in a more organised fashion.  Any existing tickets you have created along with their current status are shown below.</p>
</div>
<div class="w-1/2 mx-auto my-10">
	<form method="POST" action="/support" enctype="multipart/form-data">
	@csrf                  			
		<div class="mb-10">
			<label class="font-bold">Enter a title for this ticket:</label>
			<div class="text-red-500">{{ $errors->has('title') ? 'A title is required' : '' }}</div>
			<input class="h-8 border border-zinc-300 w-full mt-2 rounded p-4" type="text" id="title" name="title"  value="{{ old('title') }}">
		</div>  

		<div class="">
			<label class="font-bold">Enter notes:</label>
            <div class="mt-2 text-red-500">{{ $errors->has('text') ? 'At least some text is required' : '' }}</div>
			<textarea class="w-full border border-zinc-300 h-40 mt-2 rounded p-4" name="text" id="text" placeholder="Be as detailed as possible">{{ old('text') }}</textarea>
		</div>
		<div class="mt-5">
			<button type="submit" class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition">Submit Ticket</button>
		</div>
	</form>
	</div>
@endsection
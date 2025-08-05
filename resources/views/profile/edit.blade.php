@extends('layouts.app')

@section('content')

<div class="">

    <div class="">
        @if (session('status'))
            <div class="text-center text-green-800 text-2xl font-bold my-5 border py-1 px-2 bg-gray-100">
                {{ session('status') }}
            </div>
        @endif
    </div>
    
        <form action="/profile/{{ $user->name_slug }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    
        @can('Admin')
        <div class="border p-2 bg-red-50 text-sm">
            <!-- Allows an Admin to alter user groups, set trusted, add notes, remove user lock -->
                    <div class="text-center">
                            <p class="font-bold">Admin Controls</p>
                        </div>
    
                        <div class="my-4 flex justify-center space-x-4">
                            @foreach ($roles as $role)
                                <div class="">
                                    <input class="" type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                        
                                        @foreach ($user->user_roles as $user_role)
                                            @if ($user_role->name === $role->name)
                                                checked
                                            @endif
                                        @endforeach
                                        >
                                    <label class="" for="roles">{{ $role->name }}</label>
                                </div>
                            @endforeach
                        </div>
    
                        <div class="flex justify-center">       
                                <ul class="flex space-x-4">           
                                    <li class="">
                                        <label>Trusted Member?</label>     
                                        <input type="checkbox" class="" id="trusted" name="trusted" @if ($user->trusted === 1) checked=checked @endif>
                                    </li>
                                    <li class="">
                                        <label>User Locked?</label>     
                                        <input type="checkbox" class="" id="lock" name="lock" @if ($user->lock === 1) checked=checked @endif>
                                    </li>
                                </ul>
                        </div>
                                  
                        <div class="flex flex-col">
                            <label class="font-bold">Notes</label>
                            <textarea class="text-sm p-2" name="notes" id="notes">{{ $user->notes }}</textarea>
                        </div> 
                      
                    @endcan
        </div>
    
        <div class="my-10 flex flex-col items-center space-y-10 md:flex-row md:justify-evenly">
                <!-- Manage the user avatar -->
                
                <div class="flex flex-col items-center w-full">
                    <p class="py-2 text-sm text-gray-700">Upload a new avatar (JPG, PNG, GIF, max 500kb)</p>

                    <div class="flex items-center space-x-4">
                        <label for="image" class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium py-1 px-4 rounded-md transition">
                            Choose File
                        </label>
                        <span id="file-name" class="text-sm text-gray-600 dark:text-gray-400">No file chosen</span>
                    </div>

                    <input id="image" type="file" name="image" accept="image/*" onchange="loadFile(event)" class="hidden">
                    <p class="text-red-500 mt-2 text-sm">{{ $errors->has('image') ? 'Image must be JPG, JPEG, PNG or GIF. Cannot be larger than 500kb' : '' }}</p>
                </div>

                <script>
                    var loadFile = function(event) {
                        var new_avatar = document.getElementById('new_avatar');
                        var fileName = document.getElementById('file-name');
                        new_avatar.src = URL.createObjectURL(event.target.files[0]);
                        fileName.textContent = event.target.files[0].name;
                    };
                </script>
                
                <div class="w-full">
                    <p class="text-center mb-2 text-gray-700 text-sm">Existing Avatar</p>
                    <img src="{{ asset("/assets/images/avatars/$user->avatar") }}" class="mx-auto shadow-md rounded" style="width: 100px; height: 100px;">
                </div>
      
                <div class="w-full">
                    <p class="text-center mb-2 text-gray-700 text-sm">New Avatar here (if added)</p>
                    <img class="" id="new_avatar" name="new_avatar">
                </div>		
        </div>
    
        <div class="my-10 w-full md:w-1/2 md:mx-auto">
            <div class="flex flex-col space-y-6">
                  <div class="">
                    <label for="name" class="font-semibold ">Username (Locked, open a ticket to change username)</label>
                    <input type="text" class="border rounded-md w-full bg-gray-100 p-2" id="name" name="name" value="{{ $user->name }}" disabled>
                    <p class="text-red-500">{{ $errors->has('name') ? ' Cannot be blank, Max 255 characters, must be unique.' : '' }}</p>
                  </div>
    
                  <div class="">
                    <label for="email" class="">Email</label>
                    <input type="text" class="border rounded-md w-full p-2" id="email" name="email" value="{{ $user->email }}">
                    <p class="text-red-500">{{ $errors->has('email') ? ' Cannot be blank, Max 255 characters, must be unique.' : '' }}</p>
                  </div>
    
                  <div class="text-center pt-4">
                    <input type="checkbox" class="" id="email_visible" name="email_visible" value="{{ $user->email_visible }}" @if ($user->email_visible == true) checked @endif>
                    <label class="" for="email_visible">Checking this box will display your email publically.</label>
                  </div>
    
                  <div class="">
                    <label for="website" class="">Website</label>
                    <input type="text" class="border rounded-md w-full p-2" id="website" name="website" value="{{ $user->website }}">
                  </div>
    
                  <div class="">
                    <label for="location" class="">Location</label>
                    <input type="text" class="border rounded-md w-full p-2" id="location" name="location" value="{{ $user->location }}">
                  </div>
    
                  <div class="">
                    <label for="bio" class="">Tell everyone a bit about you</label>
                    <textarea class="border rounded-md w-full p-2" id="bio" name="bio" rows="6">{{ $user->bio }}</textarea>
                    <p class="text-red-500">{{ $errors->has('bio') ? ' Max 500 characters' : '' }}</p>
                  </div>
    
                  <div class="">
                    <label for="linkedin" class="">Linkedin</label>
                    <input type="text" class="border rounded-md w-full p-2" id="linkedin" name="linkedin" value="{{ $user->linkedin }}">
                  </div>
    
                  <div class="">
                    <label for="twitter" class="">Twitter</label>
                    <input type="text" class="border rounded-md w-full p-2" id="x" name="x" value="{{ $user->x }}">
                  </div>
    
                  <div class="">
                    <label for="facebook" class="">Facebook</label>
                    <input type="text" class="border rounded-md w-full p-2" id="facebook" name="facebook" value="{{ $user->facebook }}">
                  </div>
    
                  <button type="submit" class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition md:w-1/3 md:mx-auto">Update Profile</button>
            </form>
            </div>
        </div>
    </div>
    
    </div>

@endsection
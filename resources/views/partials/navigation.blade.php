        <div class="flex flex-col md:flex-row justify-between items-center relative">
            <!-- Logo -->
            <div class="mt-6">
                <a href="/"><h1 class="font-bold text-2xl text-lime-600">StarterHost<span class="text-zinc-600 text-base">.uk</span></h1></a>
            </div>

            <!-- Nav Section -->
            <nav class="border border-zinc-100 rounded-full py-2 shadow w-max mx-auto mt-6">
                <ul class="flex justify-center text-sm space-x-4 px-4 items-center">
                    <li><a href="/" class="">Home</a></li>
                    <li><a href="/blog" class="">Blog</a></li>
                    <li><a href="/about" class="">About</a></li>
                    <li><a href="/contact" class="">Contact</a></li>

                    @if(Auth::check())
                        <!-- Dropdown Trigger -->
                        <li class="relative">
                            <button id="userMenuButton" class="flex items-center gap-1 focus:outline-none cursor-pointer">
                                {{ Auth::user()->name }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="userDropdown" class="absolute right-0 mt-4 w-32 bg-white border border-slate-200 translate-x-4 rounded-xl shadow-lg z-50 hidden">
                                <div class="">
                                    <a href="/profile/{{ Auth::user()->name_slug }}" class="block px-4 py-2 hover:bg-zinc-100">Profile</a>
                                    <a href="/hosting/" class="block px-4 py-2 hover:bg-zinc-100">Hosting</a>
                                    <a href="/support/" class="block px-4 py-2 hover:bg-zinc-100">Support</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-zinc-100 hover:text-teal-500 cursor-pointer">Logout</button>
                                        </form>
                                    @can('Admin')
                                        <a href="/admin" class="block px-4 py-2 hover:bg-zinc-100 text-orange-800 font-bold">Admin</a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @else
                        <li><a href="/login" class="">Login</a></li>
                        <li><a href="/register" class="">Register</a></li>
                    @endif
                </ul>
            </nav>
        </div>
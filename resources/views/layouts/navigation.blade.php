<nav x-data="{ open: false }" class="sticky top-0 w-full z-50 bg-white border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 h-16 md:h-20 flex items-center justify-between">
        
        <!-- Logo -->
        <a href="/" class="flex items-center space-x-2 shrink-0">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-600 flex items-center justify-center text-white shadow-md">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex flex-col -space-y-1">
                <span class="text-lg md:text-xl font-extrabold tracking-tight text-slate-800">Phone<span class="text-blue-600">BD</span></span>
                <span class="text-[8px] md:text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400">Discussion</span>
            </div>
        </a>

        <!-- Search Bar (Desktop Only) -->
        <div class="hidden lg:flex flex-1 max-w-md xl:max-w-lg mx-6 xl:mx-12">
            <form action="{{ route('search') }}" method="GET" class="w-full relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for phones or topics..." class="w-full pl-10 pr-4 py-2 bg-slate-100 border-none text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </form>
        </div>

        <!-- Navigation Links (Desktop) -->
        <div class="hidden md:flex items-center space-x-4 xl:space-x-8">
            <a href="/" class="text-[10px] xl:text-xs font-extrabold uppercase tracking-widest {{ request()->routeIs('home') ? 'text-blue-600' : 'text-slate-600 hover:text-blue-600' }} transition-colors">Home</a>
            
            @auth
                @if(request()->routeIs('dashboard'))
                    <a href="/" class="text-[10px] xl:text-xs font-extrabold uppercase tracking-widest text-slate-600 hover:text-blue-600 transition-colors">Community</a>
                @else
                    <a href="{{ route('dashboard') }}" class="text-[10px] xl:text-xs font-extrabold uppercase tracking-widest text-slate-600 hover:text-blue-600 transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-600' : '' }}">Dashboard</a>
                @endif
                
                <!-- User Profile Dropdown -->
                <div class="ms-2 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-[10px] xl:text-xs font-extrabold uppercase tracking-widest text-slate-600 hover:text-blue-600 focus:outline-none transition-colors border-l border-slate-200 pl-4 py-1">
                                <span class="max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="ms-1 h-3 w-3 md:h-4 md:w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="text-[10px] font-extrabold uppercase tracking-widest text-slate-600 italic">
                                {{ __('My Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="text-[10px] font-extrabold uppercase tracking-widest text-rose-600 italic">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-[10px] xl:text-xs font-extrabold uppercase tracking-widest text-slate-600 hover:text-blue-600 transition-colors">Login</a>
                <a href="{{ route('register') }}" class="text-[10px] xl:text-xs font-extrabold uppercase tracking-widest text-white bg-blue-600 px-4 xl:px-6 py-2 md:py-2.5 hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 whitespace-nowrap">Sign Up</a>
            @endauth
        </div>

        <!-- Hamburger (Mobile Only) -->
        <div class="md:hidden flex items-center">
            <button @click="open = ! open" class="p-2 text-slate-600 hover:text-blue-600 focus:outline-none transition-colors">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-t border-slate-100 p-4 space-y-6 shadow-xl">
        
        <!-- Mobile Search -->
        <form action="{{ route('search') }}" method="GET" class="relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search phones..." class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border-none text-sm outline-none">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </form>
        
        <div class="flex flex-col space-y-4">
            <a href="/" class="text-xs font-extrabold uppercase tracking-widest {{ request()->routeIs('home') ? 'text-blue-600' : 'text-slate-600' }}">Home</a>
            
            @auth
                <a href="{{ route('dashboard') }}" class="text-xs font-extrabold uppercase tracking-widest {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-slate-600' }}">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="text-xs font-extrabold uppercase tracking-widest text-slate-600">My Profile</a>
                <div class="pt-4 border-t border-slate-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-extrabold uppercase tracking-widest text-rose-600">Log Out Account</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-xs font-extrabold uppercase tracking-widest text-slate-600">Login</a>
                <a href="{{ route('register') }}" class="text-xs font-extrabold uppercase tracking-widest text-blue-600">Create Account</a>
            @endauth
        </div>
    </div>
</nav>

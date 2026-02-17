<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PhoneBD Discuss | Community for Mobile Enthusiasts</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { 
                font-family: 'Plus Jakarta Sans', sans-serif; 
                background-color: #f3f4f6;
            }
            .brand-blue { color: #2563eb; }
            .bg-brand-blue { background-color: #2563eb; }
            /* Global reset for any remaining radius */
            * { border-radius: 0 !important; }
        </style>
    </head>
    <body class="antialiased text-slate-900 overflow-x-hidden">
        
        <!-- Navigation Bar (PhoneBD Style) -->
        <nav class="sticky top-0 w-full z-50 bg-white border-b border-slate-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-brand-blue flex items-center justify-center text-white shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex flex-col -space-y-1">
                        <span class="text-xl font-extrabold tracking-tight text-slate-800">Phone<span class="brand-blue">BD</span></span>
                        <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400">Discussion</span>
                    </div>
                </a>

                <!-- Search (Header Style) -->
                <div class="hidden md:flex flex-1 max-w-lg mx-12">
                    <form action="{{ route('search') }}" method="GET" class="w-full relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for phones or topics..." class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border-none text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </form>
                </div>

                <!-- Nav Links -->
                <div class="flex items-center space-x-6">
                    <a href="/" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Home</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-white bg-brand-blue px-6 py-2.5 hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-brand-blue px-6 py-2.5 hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">Sign Up</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="max-w-7xl mx-auto px-4 py-10">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- Center Column: Discussions -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-none">
                                {{ isset($isSearch) ? 'Search Results' : 'Community Feed' }}
                            </h1>
                            <p class="mt-2 text-sm text-slate-500 font-medium tracking-tight">
                                {{ isset($isSearch) ? 'Found products matching your search' : 'Real experiences from Bangladeshi users' }}
                            </p>
                        </div>
                        @if(!isset($isSearch))
                        <div class="flex bg-white p-1 border border-slate-200 shadow-sm">
                            <a href="{{ route('home', ['sort' => 'recent']) }}" class="px-4 py-1.5 text-xs font-bold transition-all {{ request('sort', 'recent') === 'recent' ? 'bg-brand-blue text-white' : 'text-slate-600 hover:bg-slate-50' }}">Recent</a>
                            <a href="{{ route('home', ['sort' => 'popular']) }}" class="px-4 py-1.5 text-xs font-bold transition-all {{ request('sort') === 'popular' ? 'bg-brand-blue text-white' : 'text-slate-600 hover:bg-slate-50' }}">Popular</a>
                        </div>
                        @endif
                    </div>

                    <!-- Content List -->
                    <div class="space-y-4">
                        @if(isset($isSearch))
                            @forelse($phones as $phone)
                                <div class="bg-white p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-100 transition-all group relative overflow-hidden">
                                    <div class="flex gap-6 items-center">
                                        <!-- Stats -->
                                        <div class="hidden sm:flex flex-col items-center justify-center space-y-2 w-20 shrink-0 py-2 bg-slate-50 border border-slate-100">
                                            <span class="text-lg font-extrabold text-blue-600">{{ $phone->discussions_count }}</span>
                                            <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Posts</span>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1">
                                            <a href="{{ route('discussions.show', $phone->slug) }}" class="block">
                                                <h2 class="text-lg font-extrabold text-slate-900 group-hover:text-blue-600 transition-colors mb-1 leading-tight">
                                                    {{ $phone->name }}
                                                </h2>
                                                <p class="text-sm text-slate-500 font-medium">
                                                    Start or join the conversation about {{ $phone->name }}.
                                                </p>
                                            </a>
                                        </div>

                                        <div class="shrink-0">
                                            <a href="{{ route('discussions.show', $phone->slug) }}" class="inline-flex items-center justify-center p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white p-20 text-center border-2 border-dashed border-slate-200">
                                    <p class="text-slate-400 font-bold tracking-tight">Unlucky! No products found for "{{ request('q') }}".</p>
                                </div>
                            @endforelse
                        @else
                            @forelse($discussions as $discussion)
                                @if($discussion->phone)
                                    <div class="bg-white p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-100 transition-all group relative overflow-hidden">
                                        <div class="flex gap-6">
                                            <!-- Stats -->
                                            <div class="hidden sm:flex flex-col items-center space-y-4 w-16 shrink-0">
                                                <div class="text-center">
                                                    <span class="block text-lg font-extrabold text-slate-800">{{ $discussion->votes_count }}</span>
                                                    <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Votes</span>
                                                </div>
                                                <div class="text-center p-2 border border-blue-50 bg-blue-50/30">
                                                    <span class="block text-sm font-extrabold text-blue-600">{{ $discussion->replies_count }}</span>
                                                    <span class="text-[9px] uppercase font-bold text-blue-400">Replies</span>
                                                </div>
                                            </div>

                                            <!-- Content -->
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-3">
                                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-500 text-[10px] font-extrabold uppercase shadow-sm">
                                                        {{ $discussion->phone->name }}
                                                    </span>
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                        {{ $discussion->created_at->diffForHumans() }}
                                                    </span>
                                                </div>

                                                <a href="{{ route('discussions.show', $discussion->phone->slug) }}#discussion-{{ $discussion->id }}" class="block">
                                                    <h2 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors mb-2 leading-tight">
                                                        {{ $discussion->phone->name }} Discussion
                                                    </h2>
                                                    <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed font-medium mb-4">
                                                        {{ $discussion->content }}
                                                    </p>
                                                </a>

                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        <div class="w-6 h-6 bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-200">
                                                            {{ substr($discussion->author_name, 0, 1) }}
                                                        </div>
                                                        <span class="text-xs font-bold text-slate-500 tracking-tight">{{ $discussion->author_name }}</span>
                                                    </div>
                                                    <a href="{{ route('discussions.show', $discussion->phone->slug) }}" class="text-xs font-bold text-blue-600 hover:underline">View Discussion →</a>
                                                </div>
                                            </div>
                                        </div>
                                        @if($discussion->is_pinned)
                                            <div class="absolute top-0 right-0">
                                                <div class="bg-amber-100 text-amber-700 text-[8px] font-extrabold uppercase px-3 py-1 border-l border-b border-amber-200">Pinned</div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @empty
                                <div class="bg-white p-20 text-center border-2 border-dashed border-slate-200">
                                    <p class="text-slate-400 font-bold tracking-tight">No discussions found yet. Start the first one!</p>
                                </div>
                            @endforelse
                        @endif
                    </div>

                    <div class="mt-12">
                        @if(isset($isSearch))
                            {{ $phones->links() }}
                        @else
                            {{ $discussions->links() }}
                        @endif
                    </div>
                </div>

                <!-- Right Sidebar: Suggested Sites Style -->
                <div class="hidden lg:block w-80 space-y-8">
                    <!-- Trending Phones -->
                    <div class="bg-white border border-slate-200 shadow-sm p-6">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-4 underline decoration-blue-500 decoration-2 underline-offset-4">Trending Now</h3>
                        <div class="space-y-3">
                            @foreach($trendingPhones as $phone)
                                <a href="{{ route('discussions.show', $phone->slug) }}" class="flex items-center justify-between p-3 bg-slate-50 hover:bg-blue-50 transition-all border border-transparent hover:border-blue-100 group">
                                    <span class="text-xs font-bold text-slate-600 group-hover:text-blue-600 line-clamp-1">{{ $phone->name }}</span>
                                    <span class="text-[10px] font-bold text-blue-600 bg-white border border-blue-100 px-2 py-0.5">Hot</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Coming Soon / Featured -->
                    <div class="bg-brand-blue p-8 text-white shadow-xl shadow-blue-100 relative overflow-hidden group">
                        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
                        <h3 class="text-lg font-extrabold leading-tight mb-4 relative z-10">Pro Advice</h3>
                        <p class="text-blue-100 text-[10px] font-bold leading-relaxed mb-6 relative z-10 uppercase tracking-widest">Read real user experiences before you buy your next device.</p>
                        <div class="bg-white/10 p-4 border border-white/20 relative z-10">
                            <span class="text-[9px] font-bold uppercase tracking-widest text-blue-200">Featured Topic</span>
                            <p class="text-xs font-bold mt-1">Best Smartphones for Gaming in 2026</p>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- Dark Footer (PhoneBD Style) -->
        <footer class="bg-slate-900 pt-20 pb-10">
            <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-12 mb-16 border-b border-slate-800 pb-16">
                <!-- Brand -->
                <div class="space-y-6">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 flex items-center justify-center text-white font-bold">P</div>
                        <span class="text-xl font-extrabold text-white">PhoneBD</span>
                    </a>
                    <p class="text-xs font-bold text-slate-400 leading-relaxed">
                        The leading mobile community in Bangladesh. We provide real user data for smarter tech buying.
                    </p>
                    <div class="flex space-x-4">
                        <div class="w-8 h-8 bg-slate-800 flex items-center justify-center text-white hover:bg-blue-600 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" /></svg>
                        </div>
                    </div>
                </div>

                <!-- Links -->
                <div class="space-y-6">
                    <h4 class="text-xs font-extrabold text-white uppercase tracking-widest">Company</h4>
                    <ul class="space-y-4 text-xs font-bold text-slate-500">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>

                <div class="space-y-6">
                    <h4 class="text-xs font-extrabold text-white uppercase tracking-widest">Support</h4>
                    <ul class="space-y-4 text-xs font-bold text-slate-500">
                        <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Staff Portal</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Community Rules</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="space-y-6">
                    <h4 class="text-xs font-extrabold text-white uppercase tracking-widest">Stay Updated</h4>
                    <form class="space-y-4">
                        <input type="email" placeholder="Email address" class="w-full px-4 py-3 bg-slate-800 border-none text-xs text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                        <button class="w-full py-3 bg-blue-600 text-white text-xs font-extrabold hover:bg-blue-700 transition-all">Subscribe Now</button>
                    </form>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-[10px] font-bold text-slate-600 tracking-widest uppercase">
                    &copy; {{ date('Y') }} PhoneBD Discuss. Made with passion for Bangladesh.
                </div>
                <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                    <span>Server Status:</span>
                    <span class="text-emerald-500 animate-pulse">Operational</span>
                </div>
            </div>
        </footer>
    </body>
</html>

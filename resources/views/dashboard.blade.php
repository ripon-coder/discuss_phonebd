<x-app-layout>
    <style>
        /* Force no border radius */
        * { border-radius: 0 !important; }
        .dashboard-container { background-color: #f3f4f6; min-h-screen; }
        .metric-card { border-left: 4px solid #2563eb; }
    </style>

    <div class="max-w-7xl mx-auto sm:px-4 lg:px-6 py-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <!-- Metrics Row -->
            <div class="metric-card bg-white p-3 border border-slate-200 shadow-sm">
                <div class="text-slate-400 text-[9px] font-extrabold uppercase tracking-[0.1em] mb-1">Discussions</div>
                <div class="text-xl font-extrabold text-slate-900">{{ $discussionsCount }}</div>
            </div>
            
            <div class="metric-card bg-white p-3 border border-slate-200 shadow-sm border-l-indigo-600">
                <div class="text-slate-400 text-[9px] font-extrabold uppercase tracking-[0.1em] mb-1">Total Replies</div>
                <div class="text-xl font-extrabold text-slate-900">{{ $repliesCount }}</div>
            </div>

            <div class="metric-card bg-white p-3 border border-slate-200 shadow-sm border-l-amber-500">
                <div class="text-slate-400 text-[9px] font-extrabold uppercase tracking-[0.1em] mb-1">Votes Received</div>
                <div class="text-xl font-extrabold text-slate-900">{{ $votesReceivedCount }}</div>
            </div>

            <div class="metric-card bg-white p-3 border border-slate-200 shadow-sm border-l-emerald-500">
                <div class="text-slate-400 text-[9px] font-extrabold uppercase tracking-[0.1em] mb-1">Account Rank</div>
                <div class="text-sm font-extrabold text-emerald-600 uppercase tracking-widest">
                    @if($discussionsCount > 10) Pro @else Standard @endif
                </div>
            </div>

            <!-- Main Activity Area -->
            <div class="md:col-span-3 space-y-6">
                <div class="bg-white border border-slate-200 shadow-sm">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Your Recent Activity</h3>
                        <a href="/" class="text-[10px] font-extrabold text-blue-600 uppercase tracking-widest hover:underline">Explore More →</a>
                    </div>
                    
                    <div class="p-0">
                        @forelse($activities as $activity)
                            <div class="p-6 border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="px-2 py-0.5 {{ $activity->type === 'discussion' ? 'bg-blue-100 text-blue-600' : 'bg-indigo-100 text-indigo-600' }} text-[9px] font-extrabold uppercase tracking-widest">
                                        {{ $activity->type }}
                                    </span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                
                                @if($activity->type === 'discussion')
                                    <a href="{{ route('discussions.show', $activity->phone->slug) }}#discussion-{{ $activity->id }}" class="block">
                                        <h4 class="text-sm font-extrabold text-slate-800 line-clamp-1 italic">"{{ Str::limit($activity->content, 100) }}"</h4>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tight">On {{ $activity->phone->name ?? 'Unknown Device' }}</p>
                                    </a>
                                @else
                                    <a href="{{ route('discussions.show', $activity->discussion->phone->slug) }}#reply-{{ $activity->id }}" class="block">
                                        <h4 class="text-sm font-extrabold text-slate-800 line-clamp-1 italic">"{{ Str::limit($activity->content, 100) }}"</h4>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tight">Reply on {{ $activity->discussion->phone->name ?? 'Unknown Device' }}</p>
                                    </a>
                                @endif
                                
                                <div class="mt-3 flex items-center space-x-2">
                                    <span class="text-[9px] font-bold uppercase tracking-widest {{ $activity->status === 'approved' ? 'text-emerald-500' : 'text-amber-500' }}">
                                        Status: {{ ucfirst($activity->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="p-20 text-center">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 mx-auto flex items-center justify-center text-slate-300 mb-6">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-800 uppercase tracking-widest">No Activity Record</h4>
                                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-tight mt-2 max-w-xs mx-auto">Start sharing your mobile experiences on product pages to earn reputation.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Dashboard Sidebar -->
            <div class="md:col-span-1 space-y-6">
                <div class="bg-slate-900 p-6 text-white border border-slate-800 shadow-lg">
                    <h3 class="text-sm font-extrabold uppercase tracking-[0.2em] mb-4 text-blue-400">Contributor Perk</h3>
                    <p class="text-[11px] font-bold text-slate-400 leading-relaxed mb-6 uppercase">Verified users receive instant approval for all community posts and exclusive profile badges.</p>
                    <button class="w-full py-3 bg-blue-600 text-white text-[10px] font-extrabold uppercase tracking-widest hover:bg-blue-700 transition-colors">Apply Verification</button>
                </div>

                <div class="bg-white p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest mb-4">Account Help</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors">Community Rules</a></li>
                        <li><a href="{{ route('profile.edit') }}" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors">Manage Profile</a></li>
                        <li><a href="#" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors">Privacy Settings</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $phone->name }} - Discussions | PhoneBD</title>
    <meta name="description" content="Join the discussion about {{ $phone->name }}. Ask questions, share experiences, and get answers from the community.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-full">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $phone->name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">Community Discussions</p>
                    </div>
                    @auth
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Logout</button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Login</a>
                            <a href="{{ route('register') }}" class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Sign Up</a>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Ask Question Form -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Ask a Question</h2>
                <form method="POST" action="{{ route('discussions.store', $phone->slug) }}" x-data="{ content: '' }">
                    @csrf
                    @guest
                        <div class="mb-4">
                            <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-2">Your Name (Optional)</label>
                            <input type="text" id="guest_name" name="guest_name" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Anonymous">
                        </div>
                    @endguest
                    
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Your Question</label>
                        <textarea id="content" name="content" rows="4" required x-model="content"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Ask your question about {{ $phone->name }}..."></textarea>
                        <p class="mt-1 text-sm text-gray-500">Maximum 1 external link allowed. HTML will be sanitized.</p>
                    </div>
                    
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Post Question
                    </button>
                </form>
            </div>

            <!-- Discussions List -->
            <div class="space-y-6">
                @forelse($discussions as $discussion)
                    <div class="bg-white rounded-lg shadow-sm p-6" id="discussion-{{ $discussion->id }}">
                        <!-- Discussion Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr($discussion->author_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900">{{ $discussion->author_name }}</span>
                                        @if($discussion->user && $discussion->user->is_trusted)
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded">Verified</span>
                                        @endif
                                        @if($discussion->is_pinned)
                                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded">📌 Pinned</span>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $discussion->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Discussion Content -->
                        <div class="prose max-w-none mb-4">
                            {!! nl2br(e($discussion->content)) !!}
                        </div>

                        <!-- Discussion Actions -->
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('discussions.vote', [$phone->slug, $discussion->id]) }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Helpful ({{ $discussion->votes_count }})</span>
                                </button>
                            </form>

                            <button @click="$refs.replyForm{{ $discussion->id }}.classList.toggle('hidden')" 
                                    class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                <span class="text-sm font-medium">Reply ({{ $discussion->replies->count() }})</span>
                            </button>

                            <button @click="$refs.reportForm{{ $discussion->id }}.classList.toggle('hidden')" 
                                    class="flex items-center gap-2 text-gray-600 hover:text-red-600 transition-colors ml-auto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                                </svg>
                                <span class="text-sm font-medium">Report</span>
                            </button>
                        </div>

                        <!-- Reply Form -->
                        <div x-ref="replyForm{{ $discussion->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('discussions.reply', [$phone->slug, $discussion->id]) }}">
                                @csrf
                                @guest
                                    <div class="mb-3">
                                        <input type="text" name="guest_name" 
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="Your Name (Optional)">
                                    </div>
                                @endguest
                                
                                <textarea name="content" rows="3" required
                                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-3"
                                          placeholder="Write your reply..."></textarea>
                                
                                <button type="submit" 
                                        class="bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                    Post Reply
                                </button>
                            </form>
                        </div>

                        <!-- Report Form -->
                        <div x-ref="reportForm{{ $discussion->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('discussions.report', [$phone->slug, $discussion->id]) }}">
                                @csrf
                                <textarea name="reason" rows="2" required
                                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent mb-3"
                                          placeholder="Why are you reporting this?"></textarea>
                                
                                <button type="submit" 
                                        class="bg-red-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-red-700 transition-colors">
                                    Submit Report
                                </button>
                            </form>
                        </div>

                        <!-- Replies -->
                        @if($discussion->replies->count() > 0)
                            <div class="mt-6 pl-6 border-l-2 border-gray-200 space-y-4">
                                @foreach($discussion->replies as $reply)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                                {{ substr($reply->author_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-gray-900 text-sm">{{ $reply->author_name }}</span>
                                                    @if($reply->user && $reply->user->is_trusted)
                                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded">Verified</span>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="prose prose-sm max-w-none">
                                            {!! nl2br(e($reply->content)) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No discussions yet</h3>
                        <p class="text-gray-600">Be the first to ask a question about {{ $phone->name }}!</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($discussions->hasPages())
                <div class="mt-8">
                    {{ $discussions->links() }}
                </div>
            @endif
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-sm text-gray-600">
                    © {{ date('Y') }} PhoneBD Discussions. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>

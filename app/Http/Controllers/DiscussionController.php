<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Models\DiscussionVote;
use App\Models\DiscussionReport;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class DiscussionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('q') && !empty($request->get('q'))) {
            $search = $request->get('q');
            
            // Product-based search
            $phones = Phone::where('is_published', true)
                ->where('title', 'like', "%{$search}%")
                ->paginate(15);

            // Manually fetch counts since withCount doesn't work across connections
            $phoneIds = $phones->pluck('id')->toArray();
            $counts = Discussion::whereIn('phone_id', $phoneIds)
                ->where('status', 'approved')
                ->selectRaw('phone_id, count(*) as count')
                ->groupBy('phone_id')
                ->pluck('count', 'phone_id');

            foreach ($phones as $phone) {
                $phone->setAttribute('discussions_count', $counts[$phone->id] ?? 0);
            }

            return view('welcome', [
                'phones' => $phones,
                'isSearch' => true
            ]);
        }

        // Default home feed with sorting
        $sort = $request->get('sort', 'recent');
        
        $query = Discussion::where('status', 'approved')
            ->with(['phone', 'user'])
            ->withCount(['votes', 'replies' => function($query) {
                $query->where('status', 'approved');
            }]);

        if ($sort === 'popular') {
            $query->orderBy('replies_count', 'desc')
                  ->orderBy('votes_count', 'desc')
                  ->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('is_pinned', 'desc')
                  ->orderBy('created_at', 'desc');
        }

        $discussions = $query->paginate(15);

        // Sidebar data: Top 5 most discussed phones
        $trendingPhoneIds = Discussion::where('status', 'approved')
            ->selectRaw('phone_id, count(*) as count')
            ->groupBy('phone_id')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->pluck('phone_id');

        $trendingPhones = Phone::whereIn('id', $trendingPhoneIds)
            ->where('is_published', true)
            ->get();

        return view('welcome', compact('discussions', 'trendingPhones'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    public function show($slug)
    {
        $phone = Phone::where('slug', $slug)->firstOrFail();
        
        $discussions = Discussion::where('phone_id', $phone->id)
            ->where('status', 'approved')
            ->with(['user', 'replies' => function($query) {
                $query->where('status', 'approved')->with('user');
            }, 'votes'])
            ->withCount('votes')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('discussions.show', compact('phone', 'discussions'));
    }

    public function store(Request $request, $slug)
    {
        $phone = Phone::where('slug', $slug)->firstOrFail();
        
        // Rate limiting
        $key = 'discussion:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['error' => "Too many attempts. Please try again in {$seconds} seconds."]);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'guest_name' => 'nullable|string|max:255',
        ]);

        // Check for duplicate content
        $recentDuplicate = Discussion::where('ip_address', $request->ip())
            ->where('content', $validated['content'])
            ->where('created_at', '>', now()->subHours(24))
            ->exists();

        if ($recentDuplicate) {
            return back()->withErrors(['error' => 'Duplicate content detected.']);
        }

        // Count external links
        $linkCount = substr_count($validated['content'], 'http://') + substr_count($validated['content'], 'https://');
        if ($linkCount > 1) {
            return back()->withErrors(['error' => 'Maximum 1 external link allowed.']);
        }

        // Sanitize HTML
        $content = strip_tags($validated['content'], '<p><br><strong><em><ul><ol><li>');

        $discussion = Discussion::create([
            'phone_id' => $phone->id,
            'user_id' => auth()->id(),
            'guest_name' => auth()->check() ? null : ($validated['guest_name'] ?? null),
            'content' => $content,
            'ip_address' => $request->ip(),
            'status' => (auth()->check() && auth()->user()->is_trusted) ? 'approved' : 'pending',
        ]);

        RateLimiter::hit($key, 3600); // 1 hour decay

        if ($discussion->status === 'pending') {
            return back()->with('success', 'Your question has been submitted and is pending approval.');
        }

        return back()->with('success', 'Your question has been posted successfully!');
    }

    public function storeReply(Request $request, $slug, Discussion $discussion)
    {
        $phone = Phone::where('slug', $slug)->firstOrFail();
        
        // Rate limiting
        $key = 'reply:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['error' => "Too many attempts. Please try again in {$seconds} seconds."]);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'guest_name' => 'nullable|string|max:255',
        ]);

        // Check for duplicate content
        $recentDuplicate = DiscussionReply::where('ip_address', $request->ip())
            ->where('content', $validated['content'])
            ->where('created_at', '>', now()->subHours(24))
            ->exists();

        if ($recentDuplicate) {
            return back()->withErrors(['error' => 'Duplicate content detected.']);
        }

        // Count external links
        $linkCount = substr_count($validated['content'], 'http://') + substr_count($validated['content'], 'https://');
        if ($linkCount > 1) {
            return back()->withErrors(['error' => 'Maximum 1 external link allowed.']);
        }

        // Sanitize HTML
        $content = strip_tags($validated['content'], '<p><br><strong><em><ul><ol><li>');

        $reply = DiscussionReply::create([
            'discussion_id' => $discussion->id,
            'user_id' => auth()->id(),
            'guest_name' => auth()->check() ? null : ($validated['guest_name'] ?? null),
            'content' => $content,
            'ip_address' => $request->ip(),
            'status' => (auth()->check() && auth()->user()->is_trusted) ? 'approved' : 'pending',
        ]);

        RateLimiter::hit($key, 3600); // 1 hour decay

        if ($reply->status === 'pending') {
            return back()->with('success', 'Your reply has been submitted and is pending approval.');
        }

        return back()->with('success', 'Your reply has been posted successfully!');
    }

    public function vote(Request $request, $slug, Discussion $discussion)
    {
        $phone = Phone::where('slug', $slug)->firstOrFail();
        
        // Check if already voted
        $existingVote = DiscussionVote::where('discussion_id', $discussion->id)
            ->where(function($query) use ($request) {
                $query->where('user_id', auth()->id())
                      ->orWhere('ip_address', $request->ip());
            })
            ->first();

        if ($existingVote) {
            return back()->withErrors(['error' => 'You have already voted on this discussion.']);
        }

        DiscussionVote::create([
            'discussion_id' => $discussion->id,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Thank you for your vote!');
    }

    public function report(Request $request, $slug, Discussion $discussion)
    {
        $phone = Phone::where('slug', $slug)->firstOrFail();
        
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        DiscussionReport::create([
            'discussion_id' => $discussion->id,
            'reason' => $validated['reason'],
        ]);

        return back()->with('success', 'Thank you for reporting. We will review this content.');
    }
}

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

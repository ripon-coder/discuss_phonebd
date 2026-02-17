<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Models\DiscussionVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch User Stats
        $discussionsCount = Discussion::where('user_id', $user->id)->count();
        $repliesCount = DiscussionReply::where('user_id', $user->id)->count();
        $votesReceivedCount = DiscussionVote::whereHas('discussion', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        // Fetch Recent Activity (Discussions + Replies)
        $recentDiscussions = Discussion::where('user_id', $user->id)
            ->with('phone')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $item->type = 'discussion';
                return $item;
            });

        $recentReplies = DiscussionReply::where('user_id', $user->id)
            ->with(['discussion.phone'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $item->type = 'reply';
                return $item;
            });

        // Merge and sort activities
        $activities = $recentDiscussions->concat($recentReplies)
            ->sortByDesc('created_at')
            ->take(5);

        return view('dashboard', compact('discussionsCount', 'repliesCount', 'votesReceivedCount', 'activities'));
    }
}

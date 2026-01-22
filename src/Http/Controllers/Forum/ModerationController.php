<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Flag;
use Waterhole\Models\Post;

class ModerationController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth');
    }

    public function __invoke(Request $request)
    {
        if (Channel::allPermitted($request->user(), 'moderate') === []) {
            abort(403);
        }

        $pendingFlags = Flag::query()
            ->pending()
            ->selectRaw('min(id) as id, subject_type, subject_id, min(created_at) as created_at')
            ->groupBy('subject_type', 'subject_id')
            ->orderByRaw('count(*) desc')
            ->oldest()
            ->with([
                'subject' => function (MorphTo $morph) {
                    $morph->morphWith([
                        Post::class => ['user', 'channel', 'pendingFlags'],
                        Comment::class => ['user', 'channel', 'pendingFlags'],
                    ]);
                },
            ])
            ->cursorPaginate(10);

        return view('waterhole::forum.moderation', compact('pendingFlags'));
    }
}

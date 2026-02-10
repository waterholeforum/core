<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Bookmark;

class SavedController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $bookmarks = Bookmark::query()
            ->whereBelongsTo($user)
            ->visible($user)
            ->with([
                'content' => fn(MorphTo $morph) => $morph->morphWith(
                    Bookmark::bookmarkableMorphWith(),
                ),
            ])
            ->latest()
            ->cursorPaginate(10);

        return view('waterhole::forum.saved', compact('bookmarks'));
    }
}

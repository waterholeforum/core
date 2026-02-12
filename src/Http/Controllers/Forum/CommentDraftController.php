<?php

namespace Waterhole\Http\Controllers\Forum;

use HotwiredLaravel\TurboLaravel\Http\TurboResponseFactory;
use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Post;
use Waterhole\View\Components\Composer;
use Waterhole\View\TurboStream;

class CommentDraftController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth');
    }

    public function store(Post $post, Request $request)
    {
        $this->authorize('waterhole.post.comment', $post);

        $data = $request->validate([
            'body' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        $post->userState->setDraft($data['body'] ?: null, $data['parent_id'] ?? null)->save();

        return back();
    }

    public function destroy(Post $post, Request $request)
    {
        $this->authorize('waterhole.post.comment', $post);

        $post->userState->discardDraft()->save();

        if ($request->wantsTurboStream()) {
            return TurboResponseFactory::makeStream(TurboStream::replace(new Composer($post)));
        }

        return redirect($post->url);
    }
}

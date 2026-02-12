<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Http\Controllers\Forum\Concerns\SavesPostDrafts;

class PostDraftController extends Controller
{
    use SavesPostDrafts;

    public function __construct()
    {
        $this->middleware('waterhole.auth');
        $this->middleware(Authorize::using('waterhole.post.create'));
    }

    public function store(Request $request)
    {
        $this->savePostDraft($request->user(), $request->all());

        return back();
    }

    public function destroy(Request $request)
    {
        $request->user()->drafts()->delete();

        return redirect(route('waterhole.home'));
    }
}

<?php

namespace Waterhole\Http\Controllers\Forum;

use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Post;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('waterhole::forum.home')
            ->with('posts', Post::query()->latest()->cursorPaginate());
    }
}

<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\PostFeed;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $feed = new PostFeed($request);

        return view('waterhole::forum.home')->with(compact('feed'));
    }
}

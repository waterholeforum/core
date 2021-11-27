<?php

namespace Waterhole\Http\Controllers\Admin;

use Feed;
use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('waterhole::admin.dashboard');
    }

    public function feed(Request $request)
    {
        $url = $request->query('url');
        $limit = (int) $request->query('limit', 3);

        Feed::$cacheDir = storage_path('waterhole/feed');
        Feed::$cacheExpire = '12 hours';

        $feed = Feed::load($url);

        return view('waterhole::admin.feed', compact('url', 'feed', 'limit'));
    }
}

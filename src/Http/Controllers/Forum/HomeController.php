<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\PostFeed;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $scope = function (Builder $query) {
            $query->whereDoesntHave('userState', function ($query) {
                $query->where('notifications', 'ignore');
            });

            $query->whereDoesntHave('channel.userState', function ($query) {
                $query->where('notifications', 'ignore');
            });
        };

        $feed = new PostFeed(
            request: $request,
            scope: $scope
        );

        return view('waterhole::forum.home')->with(compact('feed'));
    }
}

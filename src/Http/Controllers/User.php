<?php

namespace Waterhole\Http\Controllers;

use Waterhole\Frontend\FrontendView;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class User extends Controller
{
    public function __invoke(Request $request)
    {
        $userQuery = app('waterhole.userQuery');
        $userQuery['filter[username]'] = $request->route('username');
        ksort($userQuery);

        return FrontendView::forum()
            ->api(
                'users?'.Arr::query($userQuery),
                function (FrontendView $view, $document) {
                    if (! isset($document->data[0])) {
                        abort(404);
                    }

                    $view->title($document->data[0]->attributes->displayName);
                }
            );
    }
}

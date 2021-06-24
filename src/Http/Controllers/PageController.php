<?php

namespace Waterhole\Http\Controllers;

use Nyholm\Psr7\ServerRequest;

class PageController extends Controller
{
    public function __invoke(string $slug)
    {
        $page = waterhole_api(
            (new ServerRequest('GET', 'pages'))
                ->withQueryParams(['filter' => ['slug' => $slug]])
        )[0];

        return view('forum.page', compact('page'));
    }
}

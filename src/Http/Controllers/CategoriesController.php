<?php

namespace Waterhole\Http\Controllers;

use Nyholm\Psr7\ServerRequest;

class CategoriesController extends Controller
{
    public function __invoke()
    {
        $categories = waterhole_api(
            (new ServerRequest('GET', 'categories'))
                ->withQueryParams(app('waterhole.categoryListQuery'))
        );

        return view('forum.categories')->with([
            'categories' => $categories
        ]);
    }
}

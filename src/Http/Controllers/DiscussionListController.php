<?php

namespace Waterhole\Http\Controllers;

use Waterhole\Extend\DiscussionListFilter;
use Waterhole\Extend\DiscussionListRoute;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Nyholm\Psr7\ServerRequest;

class DiscussionListController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = app('waterhole.discussionListQuery');
        $search = $request->get('q');

        $routes = DiscussionListRoute::getItems();
        $route = $request->segment(1);

        if (isset($routes[$route])) {
            $query = array_merge($query, $routes[$route]);
        }

        $defaultFilter = app('waterhole.discussionListDefaultFilter');

        if ($request->route()->getName() === 'discussionList.category') {
            $categories = waterhole_api(
                (new ServerRequest('GET', 'categories'))
                    ->withQueryParams([
                        'include' => 'subscription,ancestors,children',
                        'filter' => ['slug' => $request->route('slug')]
                    ])
            );

            if (! count($categories)) {
                abort(404);
            }

            $category = $categories[0];
            $defaultFilter = $category->defaultFilter ?? $defaultFilter;
            $query['filter']['category'] = $category->id;
        }

        if ($search) {
            $defaultFilter = 'relevance';
            $query['filter']['q'] = $search;
            $query['include'] .= ',mostRelevantComment';
        }

        $filters = DiscussionListFilter::getItems();
        $activeFilter = $request->route('filter', $defaultFilter);

        if (isset($filters[$activeFilter])) {
            $query = array_replace_recursive($query, $filters[$activeFilter]);
        }

        $perPage = 30;
        $query['page'] = [
            'limit' => $perPage + 1,
            'offset' => (Paginator::resolveCurrentPage() - 1) * $perPage
        ];

        ksort($query);

        $discussions = waterhole_api((new ServerRequest('GET', 'discussions'))->withQueryParams($query));

        $paginator = new Paginator($discussions, $perPage);

        return view('forum.discussion-list', [
            'discussions' => $paginator,
            'category' => $category ?? null,
            'defaultFilter' => $defaultFilter
        ]);
    }
}

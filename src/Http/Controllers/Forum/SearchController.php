<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Search\Searcher;

/**
 * Controller for the search interface.
 */
class SearchController extends Controller
{
    public const SORTS = ['relevance', 'latest', 'top'];

    public function __construct()
    {
        $this->middleware('throttle:waterhole.search');
    }

    public function __invoke(Request $request, Searcher $searcher)
    {
        if (!($q = $request->input('q'))) {
            return view('waterhole::forum.search');
        }

        $channels = $selectedChannels = Channel::all();

        $currentSort = in_array($sort = $request->input('sort'), static::SORTS)
            ? $sort
            : static::SORTS[0];
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = (new Post())->getPerPage();

        if ($ids = explode(',', $request->query('channels'))) {
            $selectedChannels = $channels->find($ids);
        }

        $results = $searcher->search(
            q: $q,
            limit: $perPage,
            offset: ($currentPage - 1) * $perPage,
            sort: $currentSort,
            channelIds: $selectedChannels->modelKeys(),
        );

        // Depending on if we have an accurate idea of how many results there
        // are or not, we will wrap the hits in an appropriate paginator
        // instance.
        $paginatorOptions = [
            'path' => Paginator::resolveCurrentPath(),
        ];

        if ($results->exhaustiveTotal) {
            $hits = new LengthAwarePaginator(
                $results->hits,
                $results->total,
                $perPage,
                $currentPage,
                $paginatorOptions,
            );
        } else {
            $hits = new Paginator($results->hits, $perPage, $currentPage, $paginatorOptions);
        }

        // In the sidebar, we will only display channels that contain hits, and
        // we will sort them with the most hits at the top.
        $channelsByHits = $channels
            ->filter(fn($channel) => $results->channelHits[$channel->id])
            ->sortByDesc(fn($channel) => $results->channelHits[$channel->id]);

        return view('waterhole::forum.search', [
            'hits' => $hits->withQueryString(),
            'results' => $results,
            'channels' => $channelsByHits,
            'selectedChannels' => $selectedChannels,
            'sorts' => static::SORTS,
            'currentSort' => $currentSort,
        ]);
    }
}

<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Search\MySqlEngine;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($q = $request->input('q')) {
            $sorts = ['relevance', 'latest', 'top'];
            $currentSort = in_array($s = $request->input('sort'), $sorts) ? $s : $sorts[0];
            $currentPage = Paginator::resolveCurrentPage();

            $engine = new MySqlEngine();

            $results = $engine->search(
                q: $q,
                limit: $perPage = 20,
                offset: ($currentPage - 1) * $perPage,
                sort: $currentSort,
                filters: ['channel' => $request->input('channel')],
            );

            $paginatorOptions = [
                'path' => Paginator::resolveCurrentPath()
            ];

            $postsById = Post::with(
                'user',
                'channel.userState',
                'lastComment.user',
                'userState',
                'likedBy'
            )
                ->whereIn('id', collect($results->hits)->map->postId)
                ->get()
                ->keyBy('id');

            foreach ($results->hits as $hit) {
                $hit->post = $postsById[$hit->postId] ?? null;
            }

            if ($results->exhaustiveTotal) {
                $hits = new LengthAwarePaginator(
                    $results->hits,
                    $results->total,
                    $perPage,
                    $currentPage,
                    $paginatorOptions
                );
            } else {
                $hits = new Paginator($results->hits, $perPage, $currentPage, $paginatorOptions);
            }

            return view('waterhole::forum.search', [
                'hits' => $hits->withQueryString(),
                'total' => $results->total,
                'exhaustiveTotal' => $results->exhaustiveTotal,
                'channels' => Channel::all(),
                'channelHits' => $results->channelHits,
                'error' => $results->error,
                'sorts' => $sorts,
                'currentSort' => $currentSort,
            ]);
        }

        return view('waterhole::forum.search');
    }
}

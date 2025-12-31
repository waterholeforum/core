<?php

namespace Waterhole\Search;

use Exception;
use Illuminate\Support\Facades\DB;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use function Waterhole\remove_formatting;

class MySqlEngine implements EngineInterface
{
    public function search(
        string $q,
        int $limit,
        int $offset = 0,
        ?string $sort = null,
        array $channelIds = [],
        array $in = ['title', 'body', 'comments'],
    ): Results {
        // Build a query that finds posts that match the search query, or that
        // contain comments that match the search query. This will form the
        // basis of both the search results, and the channel hit breakdown.
        $query = Post::where(function ($query) use ($in, $q) {
            if (in_array('title', $in)) {
                $query->orWhereFullText('posts.title', $q);
            }

            if (in_array('body', $in)) {
                $query->orWhereFullText('posts.body', $q);
            }

            if (in_array('comments', $in)) {
                $query->orWhereFullText('comments.body', $q);
            }
        });

        if (in_array('comments', $in)) {
            $query->leftJoin('comments', 'comments.post_id', '=', 'posts.id');
        }

        // Get a breakdown of each channel and how many hits were found within
        // them. Even if we're filtering by certain channels, we still report
        // the number of hits in all channels so that they can be displayed
        // in the sidebar.
        $tablePrefix = DB::connection(config('waterhole.system.database'))->getTablePrefix();
        $channels = Channel::query()
            ->select('id')
            ->selectSub(
                $query
                    ->clone()
                    ->select(DB::raw("count(distinct {$tablePrefix}posts.id)"))
                    ->whereColumn('posts.channel_id', 'channels.id'),
                'hits',
            )
            ->get();

        // Rather than running an additional COUNT query to get the total number
        // of search results, we can sum together the channel breakdown numbers.
        if ($channelIds) {
            $query->whereIn('channel_id', $channelIds);
            $total = $channels->find($channelIds)->sum('hits');
        } else {
            $total = $channels->sum('hits');
        }

        // Now it's time to set up the query to actually get the search result
        // information we need. We will wrap a final "outer" query around this
        // to ensure that we only get one result per post, even if it contains
        // multiple relevant comments inside.
        $query->select('posts.id as post_id', 'posts.title', 'posts.body as post_body');

        $score = [];

        if (in_array('title', $in)) {
            $score[] = 'tscore';
            $query->selectRaw("MATCH ({$tablePrefix}posts.title) AGAINST (?) * 10 as tscore", [$q]);
        }

        if (in_array('body', $in)) {
            $score[] = 'pscore';
            $query->selectRaw("MATCH ({$tablePrefix}posts.body) AGAINST (?) as pscore", [$q]);
        }

        if (in_array('comments', $in)) {
            $score[] = 'cscore';
            $query
                ->addSelect('comments.id as comment_id')
                ->addSelect('comments.body as comment_body')
                ->selectRaw(
                    "ROW_NUMBER() OVER (PARTITION BY {$tablePrefix}posts.id ORDER BY MATCH ({$tablePrefix}comments.body) AGAINST (?) DESC) as r",
                    [$q],
                )
                ->selectRaw("MATCH ({$tablePrefix}comments.body) AGAINST (?) as cscore", [$q]);
        } else {
            $query->selectRaw('1 as r');
        }

        switch ($sort) {
            case 'latest':
                $query->orderByDesc('posts.created_at');
                break;

            case 'top':
                $query->orderByDesc('posts.score');
                break;

            default:
                $query->orderByRaw(implode(' + ', $score) . ' desc');
        }

        // Finally we get the query results and map them into Hit instances.
        // For each hit we highlight the relevant words and truncate the body
        // to the most relevant part.
        $highlighter = new Highlighter($q);

        $hits = DB::connection(config('waterhole.system.database'))
            ->table($query, 'p')
            ->where('r', 1)
            ->take($limit)
            ->skip($offset)
            ->get()
            ->map(function ($row) use ($highlighter) {
                $title = $highlighter->highlight($row->title);

                try {
                    $body = $highlighter->highlight(
                        $highlighter->truncate(
                            remove_formatting(
                                ($row->pscore ?? 1) >= ($row->cscore ?? 0)
                                    ? $row->post_body
                                    : $row->comment_body,
                            ),
                        ),
                    );
                } catch (Exception $e) {
                    $body = '';
                }

                return new Hit($row->post_id, $title, $body);
            });

        if ($this->containsShortWords($q)) {
            $error = __('waterhole::forum.search-keywords-too-short-message');
        }

        return new Results(
            hits: $hits->all(),
            total: $total,
            exhaustiveTotal: true,
            channelHits: $channels->pluck('hits', 'id')->toArray(),
            error: $error ?? null,
        );
    }

    private function containsShortWords(string $q): bool
    {
        preg_match_all('/\w+/', $q, $matches);

        return collect($matches[0])->some(fn($word) => strlen($word) < 3);
    }
}

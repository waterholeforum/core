<?php

namespace Waterhole\Search;

use Illuminate\Support\Facades\DB;
use s9e\TextFormatter\Utils;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

class MySqlEngine
{
    public function search(
        string $q,
        int $limit,
        int $offset = 0,
        string $sort = null,
        array $channelIds = [],
    ): Results {
        // Build a query that finds posts that match the search query, or that
        // contain comments that match the search query. This will form the
        // basis of both the search results, and the channel hit breakdown.
        $query = Post::query()
            ->leftJoin('comments', 'comments.post_id', '=', 'posts.id')
            ->where(function ($query) use ($q) {
                $query->whereRaw("MATCH (posts.title) AGAINST (? IN BOOLEAN MODE)", [$q])
                    ->orWhereRaw("MATCH (posts.body) AGAINST (? IN BOOLEAN MODE)", [$q])
                    ->orWhereRaw("MATCH (comments.body) AGAINST (? IN BOOLEAN MODE)", [$q]);
            });

        // Get a breakdown of each channel and how many hits were found within
        // them. Even if we're filtering by certain channels, we still report
        // the number of hits in all channels so that they can be displayed
        // in the sidebar.
        $channels = Channel::query()
            ->select('id')
            ->selectSub(
                $query->clone()
                    ->select(DB::raw('count(distinct posts.id)'))
                    ->whereColumn('posts.channel_id', 'channels.id'),
                'hits'
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
        $query->select(
            'posts.id as post_id',
            'comments.id as comment_id',
            'posts.title',
            'posts.body as post_body',
            'comments.body as comment_body'
        )
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY posts.id ORDER BY MATCH (comments.body) AGAINST (?) DESC) as r', [$q])
            ->selectRaw("MATCH (posts.title) AGAINST (?) * 10 as tscore", [$q])
            ->selectRaw("MATCH (posts.body) AGAINST (?) as pscore", [$q])
            ->selectRaw("MATCH (comments.body) AGAINST (?) as cscore", [$q]);

        switch ($sort) {
            case 'latest':
                $query->orderByDesc('posts.created_at');
                break;

            case 'top':
                $query->orderByDesc('posts.score');
                break;

            default:
                $query->orderByRaw('tscore + pscore + cscore desc');
        }

        // Finally we get the query results and map them into Hit instances.
        // For each hit we highlight the relevant words and truncate the body
        // to the most relevant part.
        $hits = DB::table($query, 'p')
            ->where('r', 1)
            ->take($limit)
            ->skip($offset)
            ->get()
            ->map(function ($row) use ($q) {
                $title = highlight_words($row->title, $q);

                $body = $row->pscore >= $row->cscore ? $row->post_body : $row->comment_body;
                $body = Utils::removeFormatting($body);
                $body = highlight_words(truncate_around($body, $q), $q);

                return new Hit($row->post_id, $title, $body);
            });

        if ($this->containsShortWords($q)) {
            $error = 'Your keywords are too short – try something longer!';
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

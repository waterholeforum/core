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
        array $filters = [],
    ) {
        preg_match_all('/\w+/', $q, $matches);
        $wordsTooShort = collect($matches[0])->some(fn($word) => strlen($word) < 3);

        $inner = Post::query()
            ->leftJoinRelationship('comments')
            ->where(function ($query) use ($q) {
                $query->whereRaw("MATCH (posts.title) AGAINST (? IN BOOLEAN MODE)", [$q])
                    ->orWhereRaw("MATCH (posts.body) AGAINST (? IN BOOLEAN MODE)", [$q])
                    ->orWhereRaw("MATCH (comments.body) AGAINST (? IN BOOLEAN MODE)", [$q]);
            });

        $channels = Channel::query()
            ->select('id')
            ->selectSub(
                $inner->clone()
                    ->select(DB::raw('count(distinct posts.id)'))
                    ->whereColumn('posts.channel_id', 'channels.id'),
                'hits'
            )
            ->get();

        if ($channel = $filters['channel'] ?? null) {
            $inner->where('channel_id', $channel);
            $total = $channels->find($channel)->hits;
        } else {
            $total = $channels->sum('hits');
        }

        $inner->select(
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
                $inner->orderByDesc('posts.created_at');
                break;

            case 'top':
                $inner->orderByDesc('posts.score');
                break;

            default:
                $inner->orderByRaw('tscore + pscore + cscore desc');
        }

        $hits = DB::table($inner, 'p')
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

        return new SearchResults(
            hits: $hits->all(),
            total: $total,
            exhaustiveTotal: true,
            channelHits: $channels->pluck('hits', 'id')->toArray(),
            error: $wordsTooShort ? 'Your keywords are too short – try something longer!' : null,
        );
    }
}

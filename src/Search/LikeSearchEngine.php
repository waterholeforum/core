<?php

namespace Waterhole\Search;

use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use function Waterhole\remove_formatting;

class LikeSearchEngine implements EngineInterface
{
    public function search(
        string $q,
        int $limit,
        int $offset = 0,
        ?string $sort = null,
        array $channelIds = [],
        array $in = ['title', 'body', 'comments'],
    ): Results {
        $includeComments = in_array('comments', $in, true);

        $query = Post::query()->where(function ($query) use ($in, $q) {
            if (in_array('title', $in, true)) {
                $query->orWhere('posts.title', 'like', "%$q%");
            }
            if (in_array('body', $in, true)) {
                $query->orWhere('posts.body', 'like', "%$q%");
            }
            if (in_array('comments', $in, true)) {
                $query->orWhere('comments.body', 'like', "%$q%");
            }
        });

        if ($includeComments) {
            $query->leftJoin('comments', 'comments.post_id', '=', 'posts.id');
        }

        $grammar = $query->getGrammar();
        $postIdColumn = $grammar->wrap('posts.id');

        $channels = Channel::query()
            ->select('id')
            ->selectSub(
                $query
                    ->clone()
                    ->selectRaw("count(distinct $postIdColumn)")
                    ->whereColumn('posts.channel_id', 'channels.id'),
                'hits',
            )
            ->get();

        if ($channelIds) {
            $query->whereIn('channel_id', $channelIds);
            $total = $channels->find($channelIds)->sum('hits');
        } else {
            $total = $channels->sum('hits');
        }

        switch ($sort) {
            case 'top':
                $query->orderByDesc('posts.score');
                break;

            default:
                $query->orderByDesc('posts.created_at');
        }

        $rows = $query->distinct()->select([
            'posts.id as post_id',
            'posts.title',
            'posts.body as post_body',
        ]);

        if ($includeComments) {
            $rows->addSelect('comments.body as comment_body');
        } else {
            $rows->selectRaw('null as comment_body');
        }

        $rows = $rows
            ->take($limit)
            ->skip($offset)
            ->get();

        $highlighter = new Highlighter($q);

        $hits = $rows
            ->map(
                fn($row) => new Hit(
                    $row->post_id,
                    $highlighter->highlight($row->title ?? ''),
                    $highlighter->highlight(
                        $highlighter->truncate(
                            remove_formatting(
                                $row->comment_body && in_array('comments', $in, true)
                                    ? $row->comment_body
                                    : $row->post_body ?? '',
                            ),
                        ),
                    ),
                ),
            )
            ->all();

        return new Results(
            hits: $hits,
            total: $total,
            exhaustiveTotal: true,
            channelHits: $channels->pluck('hits', 'id')->toArray(),
        );
    }
}

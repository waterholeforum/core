<?php

namespace Waterhole\Search;

use Waterhole\Extend\Query\PostFeedQuery;
use Waterhole\Models\Post;

class Searcher
{
    public function __construct(protected EngineInterface $engine) {}

    public function search(
        string $q,
        int $limit,
        int $offset = 0,
        ?string $sort = null,
        array $channelIds = [],
        array $in = ['title', 'body', 'comments'],
    ): Results {
        $results = $this->engine->search(
            q: $q,
            limit: $limit,
            offset: $offset,
            sort: $sort,
            channelIds: $channelIds,
            in: $in,
        );

        // The engine has given us a Results object with an array of Hits,
        // each of which contain a post ID and highlighted title/body text. So
        // we still need to retrieve and set the Post model for each hit.
        $query = Post::whereIn('id', collect($results->hits)->map->postId);

        foreach (resolve(PostFeedQuery::class)->values() as $scope) {
            $scope($query);
        }

        $postsById = $query->get()->keyBy('id');

        foreach ($results->hits as $hit) {
            if ($hit->post = $postsById[$hit->postId] ?? null) {
                $hit->post->title = $hit->title;
            }
        }

        return $results;
    }
}

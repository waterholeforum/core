<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Tobyz\JsonApiServer\Laravel\SoftDeletes;
use Waterhole\Extend\Api\PostsResource as PostsResourceExtender;
use Waterhole\Models\Post;

class PostsResource extends ExtendableResource
{
    use SoftDeletes;

    public function __construct(PostsResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'posts';
    }

    public function newModel(Context $context): object
    {
        return new Post();
    }

    public function count(object $query, Context $context): ?int
    {
        return null;
    }
}

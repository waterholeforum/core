<?php

namespace Waterhole\Search;

use Illuminate\Support\HtmlString;
use Waterhole\Models\Post;

class Hit
{
    // TODO: maybe remove this and use SplObjectStorage in SearchController
    public ?Post $post;

    public function __construct(
        public int $postId,
        public HtmlString $title,
        public HtmlString $body,
    ) {
    }
}

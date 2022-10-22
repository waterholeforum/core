<?php

namespace Waterhole\Search;

use Illuminate\Support\HtmlString;
use Waterhole\Models\Post;

class Hit
{
    public ?Post $post;

    public function __construct(
        public int $postId,
        public HtmlString $title,
        public HtmlString $body,
    ) {
    }
}

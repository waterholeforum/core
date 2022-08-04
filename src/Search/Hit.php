<?php

namespace Waterhole\Search;

use Illuminate\Support\HtmlString;
use Waterhole\Models\Post;

class Hit
{
    public int $postId;

    public HtmlString $title;

    public HtmlString $body;

    public ?Post $post;

    public function __construct(
        int $postId,
        HtmlString $title,
        HtmlString $body,
    ) {
        $this->postId = $postId;
        $this->title = $title;
        $this->body = $body;
    }
}

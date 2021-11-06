<?php

namespace Waterhole\Search;

use Illuminate\Support\HtmlString;
use Waterhole\Models\Post;

class Hit
{
    public int $postId;
    public ?Post $post;
    public HtmlString $title;
    public HtmlString $body;

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

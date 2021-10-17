<?php

namespace Waterhole\Views\Components;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\View\Component;

class FeedContentList extends Component
{
    public CursorPaginator $posts;

    public function __construct(CursorPaginator $posts)
    {
        $this->posts = $posts;
    }

    public function render()
    {
        return view('waterhole::components.feed-content-list');
    }
}

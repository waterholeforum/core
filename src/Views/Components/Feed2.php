<?php

namespace Waterhole\Views\Components;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\View\Component;
use Waterhole\Feed\Feed;

class Feed2 extends Component
{
    public Feed $feed;
    public CursorPaginator $items;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
        $this->items = $feed->items()->withQueryString();
    }

    public function render()
    {
        return view('waterhole::components.feed2');
    }
}

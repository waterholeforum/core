<?php

namespace Waterhole\Views\Components;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\Component;

class InfiniteScroll extends Component
{
    public function __construct(
        public Paginator $paginator
    ) {
    }

    public function render()
    {
        return view('waterhole::components.infinite-scroll');
    }
}

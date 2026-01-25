<?php

namespace Waterhole\View\Components;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\Component;

class InfiniteScroll extends Component
{
    public function __construct(
        public Paginator|CursorPaginator $paginator,
        public bool $divider = false,
        public bool $endless = false,
    ) {}

    public function render()
    {
        return $this->view('waterhole::components.infinite-scroll');
    }
}

<?php

namespace Waterhole\Http\Controllers;

use Waterhole\Frontend\FrontendView;

class Forum extends Controller
{
    public function __invoke()
    {
        return FrontendView::forum();
    }
}

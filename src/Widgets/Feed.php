<?php

namespace Waterhole\Widgets;

use Illuminate\View\Component;

class Feed extends Component
{
    public string $url;
    public ?int $limit;

    public function __construct(string $url, int $limit = null)
    {
        $this->url = $url;
        $this->limit = $limit;
    }

    public function render()
    {
        return view('waterhole::widgets.feed');
    }
}

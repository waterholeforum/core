<?php

namespace Waterhole\Widgets;

use Feed as FeedReader;
use Illuminate\View\Component;

class Feed extends Component
{
    public static bool $lazy = true;

    public FeedReader $feed;

    public function __construct(public string $url, public int $limit = 3)
    {
        FeedReader::$cacheDir = storage_path('waterhole/feed');
        FeedReader::$cacheExpire = '12 hours';

        $this->feed = FeedReader::load($url);
    }

    public function render()
    {
        return view('waterhole::widgets.feed');
    }
}

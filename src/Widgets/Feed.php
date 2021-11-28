<?php

namespace Waterhole\Widgets;

use Feed as FeedReader;
use Illuminate\View\Component;

class Feed extends Component
{
    public static bool $lazy = true;
    
    public string $url;
    public int $limit;
    public FeedReader $feed;

    public function __construct(string $url, int $limit = 3)
    {
        $this->url = $url;
        $this->limit = $limit;

        FeedReader::$cacheDir = storage_path('waterhole/feed');
        FeedReader::$cacheExpire = '12 hours';

        $this->feed = FeedReader::load($url);
    }

    public function render()
    {
        return view('waterhole::widgets.feed');
    }
}

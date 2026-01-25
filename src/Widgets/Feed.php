<?php

namespace Waterhole\Widgets;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\Component;
use Laminas\Feed\Reader\Feed\FeedInterface;
use Laminas\Feed\Reader\Reader;

class Feed extends Component
{
    public static bool $lazy = true;

    public FeedInterface $feed;

    public function __construct(
        public string $url,
        public int $limit = 3,
        public ?string $title = null,
    ) {
        // TODO: be smarter about caching (ie. HTTP Conditional GET)
        $content = Cache::remember(
            'waterhole.feed.' . sha1($url),
            60 * 60 * 6,
            fn() => Http::throw()->get($url)->body(),
        );

        $this->feed = Reader::importString($content);
    }

    public function render()
    {
        return view('waterhole::widgets.feed');
    }
}

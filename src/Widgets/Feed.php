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
        // Keep feeds fresh for six hours, then refresh after the response
        // while allowing cached content to be served for another six hours.
        $content = Cache::flexible(
            'waterhole.feed.' . sha1($url),
            [60 * 60 * 6, 60 * 60 * 12],
            fn() => Http::throw()->get($url)->body(),
        );

        $this->feed = Reader::importString($content);
    }

    public function render()
    {
        return view('waterhole::widgets.feed');
    }
}

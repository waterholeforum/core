<?php

declare(strict_types=1);

namespace Waterhole\View\Components;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\Component;
use Waterhole\Feed\PostFeed;
use Waterhole\Filters\Ignoring;

class HomeFeed extends Component
{
    public PostFeed $feed;

    public function __construct(Request $request, ?array $filters = null, ?string $layout = null)
    {
        $this->feed = PostFeed::forIndex(
            request: $request,
            filters: $filters ?? config('waterhole.forum.post_filters', []),
            layout: resolve($layout ?? config('waterhole.forum.post_layout')),
            scope: function (Builder $query) {
                $query->withGlobalScope(Ignoring::EXCLUDE_IGNORED_SCOPE, fn($query) => $query->whereNot->ignoring());
                $query->whereDoesntHave('channel', fn($query) => $query->ignoring());
            },
        );
    }

    public function render()
    {
        return $this->view('waterhole::components.home-feed');
    }
}

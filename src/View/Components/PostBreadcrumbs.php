<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Post;

class PostBreadcrumbs extends Component
{
    public Collection $breadcrumbs;

    public function __construct(
        public Post $post,
    ) {
        $this->breadcrumbs = $post
            ->channel
            ->structure()
            ->firstOrFail()
            ->ancestorsAndSelf()
            ->withoutGlobalScopes()
            ->whereIn('content_type', [
                (new Channel())->getMorphClass(),
                (new Page())->getMorphClass(),
            ])
            ->with('content')
            ->get()
            ->reverse()
            ->values();
    }

    public function render()
    {
        return $this->view('waterhole::components.post-breadcrumbs');
    }
}

<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Model;
use Waterhole\Models\Page;
use Waterhole\Models\Structure;

class Index extends Component
{
    public Collection $breadcrumbs;
    public ?Model $content;

    public function __construct(
        public ?Structure $activeNode = null,
    ) {
        $this->content = $this->activeNode?->content;

        $this->breadcrumbs = $this->activeNode
            ? $this->activeNode
                ->ancestorsAndSelf()
                ->withoutGlobalScopes()
                ->whereIn('content_type', [
                    (new Channel())->getMorphClass(),
                    (new Page())->getMorphClass(),
                ])
                ->with('content')
                ->get()
                ->reverse()
                ->values()
            : collect();
    }

    public function render()
    {
        return $this->view('waterhole::components.index');
    }
}

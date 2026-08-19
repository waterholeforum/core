<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;

use function Waterhole\is_absolute_url;

class IndexNav extends Component
{
    public Collection $nav;

    public function __construct(
        public ?Structure $activeNode = null,
    ) {
        $activeRoot = $this->activeNode?->rootAncestorOrSelf()->first();

        $structure = Structure::query()
            ->isRoot()
            ->listed()
            ->with('content')
            ->inSiblingOrder()
            ->get()
            ->filter(fn(Structure $node) => $node->content);

        $this->nav = collect([
            new NavLink(
                label: __('waterhole::forum.home-link'),
                icon: 'tabler-home',
                route: 'waterhole.home',
                active: request()->routeIs('waterhole.home') && !$this->activeNode,
            ),
            ...$structure->map(function (Structure $node) use ($activeRoot) {
                if ($node->content instanceof StructureHeading) {
                    return new NavHeading($node->content->name ?: '');
                } elseif ($node->content instanceof Channel || $node->content instanceof Page) {
                    return new NavLink(
                        label: $node->content->name,
                        icon: $node->content->icon,
                        href: $node->content->url,
                        active: $activeRoot?->is($node),
                    );
                } elseif ($node->content instanceof StructureLink) {
                    return (new NavLink(
                        label: $node->content->name,
                        icon: $node->content->icon,
                        href: $node->content->href,
                    ))->withAttributes(
                        is_absolute_url($node->content->href) ? ['target' => '_blank'] : [],
                    );
                }

                return null;
            })->filter(),
        ]);

        // Filter out headings with no items after them
        $this->nav = $this->nav->filter(function ($item, $i) {
            if ($item instanceof NavHeading) {
                return isset($this->nav[$i + 1]) && !$this->nav[$i + 1] instanceof NavHeading;
            }

            return true;
        });

        $this->nav->push(new IndexFooter());
    }

    public function render()
    {
        return $this->view('waterhole::components.index-nav');
    }
}

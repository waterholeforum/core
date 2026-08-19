<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;

class StructureChildren extends Component
{
    public Collection $groups;

    public function __construct(Structure $parent)
    {
        $children = $parent->children()->listed()->with('content')->inSiblingOrder()->get();

        $groups = collect([
            ['heading' => null, 'items' => collect()],
        ]);

        foreach ($children as $child) {
            if ($child->content instanceof StructureHeading) {
                $groups->push(['heading' => $child->content, 'items' => collect()]);

                continue;
            }

            $groups->last()['items']->push($child);
        }

        $this->groups = $groups
            ->filter(fn(array $group) => $group['items']->isNotEmpty())
            ->values();
    }

    public function shouldRender(): bool
    {
        return $this->groups->isNotEmpty();
    }

    public function render()
    {
        return $this->view('waterhole::components.structure-children');
    }
}

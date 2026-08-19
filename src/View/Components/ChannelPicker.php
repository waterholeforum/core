<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;

class ChannelPicker extends Component
{
    public Collection $nodes;

    public array $expanded = [];

    public array $selectable = [];

    public function __construct(
        public string $name,
        public ?string $value = null,
        array $exclude = [],
        bool $showLinks = false,
        public ?string $form = null,
    ) {
        $types = [
            (new Channel())->getMorphClass(),
            (new Page())->getMorphClass(),
            (new StructureHeading())->getMorphClass(),
        ];

        if ($showLinks) {
            $types[] = (new StructureLink())->getMorphClass();
        }

        $nodes = Structure::tree()
            ->listed()
            ->whereIn('content_type', $types)
            ->with('content')
            ->inSiblingOrder()
            ->get();

        $this->selectable = $nodes
            ->filter(
                fn(Structure $node) => (
                    $node->content instanceof Channel
                    && !in_array($node->content_id, $exclude)
                    && Gate::allows('waterhole.channel.post', $node->content)
                ),
            )
            ->pluck('content_id')
            ->all();

        $selected = $nodes->first(
            fn(Structure $node) => $node->content instanceof Channel && $node->content_id == $value,
        );

        while ($selected?->parent_id) {
            $this->expanded[] = $selected->parent_id;
            $selected = $nodes->firstWhere('id', $selected->parent_id);
        }

        $this->nodes = $this->prune($nodes->toTree());
    }

    public function render()
    {
        return $this->view('waterhole::components.channel-picker');
    }

    private function prune(Collection $nodes): Collection
    {
        $nodes->each(fn(Structure $node) => $node->setRelation(
            'children',
            $this->prune($node->children),
        ));

        $nodes = $nodes
            ->filter(
                fn(Structure $node) => (
                    (
                        !$node->content instanceof Channel
                        || in_array($node->content_id, $this->selectable)
                        || $node->children->isNotEmpty()
                    )
                    && (!$node->content instanceof Page || $node->children->isNotEmpty())
                ),
            )
            ->values();

        return $nodes
            ->filter(
                fn(Structure $node, int $index) => (
                    !$node->content instanceof StructureHeading
                    || isset($nodes[$index + 1])
                    && !$nodes[$index + 1]->content instanceof StructureHeading
                ),
            )
            ->values();
    }
}

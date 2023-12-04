<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;

class ChannelPicker extends Component
{
    public Collection $items;
    public ?Channel $selectedChannel;

    public function __construct(
        public string $name,
        public ?string $value = null,
        array $exclude = [],
        bool $showLinks = false,
    ) {
        $this->items = Structure::with('content')
            ->whereMorphedTo('content', Channel::class)
            ->orWhereMorphedTo('content', StructureHeading::class)
            ->when(
                $showLinks,
                fn($query) => $query->orWhereMorphedTo('content', StructureLink::class),
            )
            ->orderBy('position')
            ->get()
            ->toBase()
            ->map->content->except($exclude)
            ->filter(
                fn($item) => !$item instanceof Channel ||
                    Gate::allows('waterhole.channel.post', $item),
            );

        // Filter out headings with no items after them
        $this->items = $this->items->filter(function ($item, $i) {
            if ($item instanceof StructureHeading) {
                return isset($this->items[$i + 1]) &&
                    !($this->items[$i + 1] instanceof StructureHeading);
            }
            return true;
        });

        $this->selectedChannel = $this->items->first(
            fn($item) => $item instanceof Channel && $item->id == $value,
        );
    }

    public function render()
    {
        return $this->view('waterhole::components.channel-picker');
    }
}

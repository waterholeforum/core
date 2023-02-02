<?php

namespace Waterhole\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;

class ChannelPicker extends Component
{
    public Collection $items;
    public ?Channel $selectedChannel;

    public function __construct(
        public string $name,
        public ?string $value = null,
        array $exclude = [],
    ) {
        $this->items = new Collection(
            Structure::with('content')
                ->whereMorphedTo('content', Channel::class)
                ->orWhereMorphedTo('content', StructureHeading::class)
                ->orderBy('position')
                ->get()
                ->map->content->except($exclude)
                ->filter(
                    fn($item) => $item instanceof StructureHeading ||
                        ($item instanceof Channel && Gate::allows('channel.post', $item)),
                ),
        );

        // Filter out headings with no items after them
        $this->items = $this->items->filter(function ($item, $i) {
            if ($item instanceof StructureHeading) {
                return isset($this->items[$i + 1]) &&
                    !($this->items[$i + 1] instanceof StructureHeading);
            }
            return true;
        });

        $this->selectedChannel = $this->items->find($value);
    }

    public function render()
    {
        return view('waterhole::components.channel-picker');
    }
}

<?php

namespace Waterhole\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Structure;

class ChannelPicker extends Component
{
    public Collection $channels;
    public ?Channel $selectedChannel;

    public function __construct(
        public string $name,
        public ?string $value = null,
        array $exclude = [],
        public bool $allowNull = false,
    ) {
        $this->channels = new Collection(
            Structure::with('content')
                ->whereMorphedTo('content', Channel::class)
                ->orderBy('position')
                ->get()
                ->except($exclude)
                ->map->content->filter(
                    fn($channel) => $channel && Gate::allows('channel.post', $channel),
                ),
        );

        $this->selectedChannel = $this->channels->find($value);
    }

    public function render()
    {
        return view('waterhole::components.channel-picker');
    }
}

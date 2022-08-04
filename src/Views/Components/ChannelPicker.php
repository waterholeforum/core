<?php

namespace Waterhole\Views\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Channel;

class ChannelPicker extends Component
{
    public string $name;

    public ?string $value;

    public array $exclude;

    public bool $allowNull;

    public Collection $channels;

    public ?Channel $selectedChannel;

    public function __construct(string $name, string $value = null, array $exclude = [], bool $allowNull = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->exclude = $exclude;
        $this->allowNull = $allowNull;

        $this->channels = Channel::all();
        $this->selectedChannel = $this->channels->find($value);
    }

    public function render()
    {
        return view('waterhole::components.channel-picker');
    }
}

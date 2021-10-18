<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\PostFeed;

class FeedControlsLayout extends Component
{
    public PostFeed $feed;
    public ?Channel $channel;

    public function __construct(PostFeed $feed, Channel $channel = null)
    {
        $this->feed = $feed;
        $this->channel = $channel;
    }

    public function render()
    {
        return <<<'blade'
            <hr class="menu-divider">
            <h4 class="menu-heading">Display as</h4>
            <a href="{{ request()->fullUrlWithQuery(['layout' => 'list']) }}" class="menu-item" role="menuitemradio">
                <x-waterhole::icon icon="heroicon-o-view-list"/>
                <span>List</span>
                @if ($feed->currentLayout() === 'list')
                    <x-waterhole::icon icon="heroicon-o-check" class="menu-item-check"/>
                @endif
            </a>
            <a href="{{ request()->fullUrlWithQuery(['layout' => 'cards']) }}" class="menu-item" role="menuitemradio">
                <x-waterhole::icon icon="heroicon-o-view-boards" class="rotate-90"/>
                <span>Cards</span>
                @if ($feed->currentLayout() === 'cards')
                    <x-waterhole::icon icon="heroicon-o-check" class="menu-item-check"/>
                @endif
            </a>
        blade;
    }
}

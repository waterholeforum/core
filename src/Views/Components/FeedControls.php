<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\User;
use Waterhole\PostFeed;

class FeedControls extends Component
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
            <ui-popup placement="bottom-end">
                <button class="btn btn--icon btn--transparent">
                    <x-waterhole::icon icon="heroicon-o-cog"/>
                </button>
                
                <ui-menu class="menu" hidden>
                    @components(Waterhole\Extend\FeedControls::getComponents(), compact('feed', 'channel'))
                </ui-menu>
            </ui-popup>
        blade;
    }
}

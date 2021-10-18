<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\User;
use Waterhole\PostFeed;

class FeedControlsMarkRead extends Component
{
    public PostFeed $feed;
    public ?Channel $channel;
    public ?User $user;

    public function __construct(PostFeed $feed, Channel $channel = null)
    {
        $this->feed = $feed;
        $this->channel = $channel;
        $this->user = Auth::user();
    }

    public function render()
    {
        return <<<'blade'
            <x-waterhole::action-button
                :for="$channel ?? $user"
                :action="Waterhole\Actions\MarkAllAsRead::class"
                class="menu-item"
                role="menuitem"
            />
        blade;
    }
}

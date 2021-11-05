<?php

namespace Waterhole\Extend;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Waterhole\Actions;
use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\Spacer;

class PostAction
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            Actions\Like::class,
            Actions\MarkAsRead::class,
            Actions\Follow::class,
            Actions\Unfollow::class,
            Actions\EditPost::class,
            Actions\MoveChannel::class,
            Spacer::class,
            Actions\DeletePost::class,
        ];
    }
}

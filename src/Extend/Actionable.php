<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesItems;
use Waterhole\Models;

class Actionable
{
    use ManagesItems;

    protected static function defaultItems(): array
    {
        return [
            'posts' => Models\Post::class,
            'channels' => Models\Channel::class,
            'comments' => Models\Comment::class,
            'structure' => Models\Structure::class,
            'users' => Models\User::class,
        ];
    }

    public static function getActionable($model): ?string
    {
        foreach (static::getItems() as $actionable => $class) {
            if ($model instanceof $class) {
                return $actionable;
            }
        }

        return null;
    }
}

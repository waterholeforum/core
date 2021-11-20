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
            'page' => Models\Page::class,
            'post' => Models\Post::class,
            'channel' => Models\Channel::class,
            'comment' => Models\Comment::class,
            'group' => Models\Group::class,
            'structureGroup' => Models\StructureHeading::class,
            'structureLink' => Models\StructureLink::class,
            'user' => Models\User::class,
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

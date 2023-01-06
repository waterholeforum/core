<?php

namespace Waterhole\Extend;

use Exception;
use Waterhole\Extend\Concerns\UnorderedList;
use Waterhole\Models;

/**
 * The list of models that can have actions applied to them.
 *
 * When Waterhole renders an action form, the ID of the model being actioned is
 * sent. But it also needs to convey what kind of model it is, so it can be
 * retrieved from the database prior to running the action. This extender keeps
 * track of the kinds of models that are allowed to be looked up by ID and have
 * actions applied to them.
 */
abstract class Actionables
{
    use UnorderedList;

    /**
     * Get the actionable name of the given model.
     */
    public static function getActionableName($model): ?string
    {
        foreach (static::build() as $actionable => $class) {
            if ($model instanceof $class) {
                return $actionable;
            }
        }

        throw new Exception(get_class($model) . ' is not actionable');
    }
}

Actionables::add('channel', Models\Channel::class);
Actionables::add('comment', Models\Comment::class);
Actionables::add('group', Models\Group::class);
Actionables::add('page', Models\Page::class);
Actionables::add('post', Models\Post::class);
Actionables::add('reactionSet', Models\ReactionSet::class);
Actionables::add('reactionType', Models\ReactionType::class);
Actionables::add('structureHeading', Models\StructureHeading::class);
Actionables::add('structureLink', Models\StructureLink::class);
Actionables::add('user', Models\User::class);

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

Actionables::add(Models\Channel::class, 'channel');
Actionables::add(Models\Comment::class, 'comment');
Actionables::add(Models\Group::class, 'group');
Actionables::add(Models\Page::class, 'page');
Actionables::add(Models\Post::class, 'post');
Actionables::add(Models\ReactionSet::class, 'reactionSet');
Actionables::add(Models\ReactionType::class, 'reactionType');
Actionables::add(Models\StructureHeading::class, 'structureHeading');
Actionables::add(Models\StructureLink::class, 'structureLink');
Actionables::add(Models\Tag::class, 'tag');
Actionables::add(Models\Taxonomy::class, 'taxonomy');
Actionables::add(Models\User::class, 'user');

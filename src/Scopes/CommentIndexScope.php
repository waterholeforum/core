<?php

namespace Waterhole\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Scope to calculate each comment's index (ie. how many comments came before it)
 * in the select clause.
 */
class CommentIndexScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if ($builder->getQuery()->columns) {
            return;
        }

        $builder->select($builder->qualifyColumn('*'))->selectSub(function ($sub) use ($builder) {
            $sub->selectRaw('count(*)')
                ->from('comments as before')
                ->whereColumn('before.post_id', $builder->qualifyColumn('post_id'))
                ->whereColumn('before.created_at', '<', $builder->qualifyColumn('created_at'));
        }, 'index');
    }
}

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

        $builder
            ->select($builder->qualifyColumn('*'))
            ->selectRaw('ROW_NUMBER() OVER (ORDER BY `created_at`) + 1 as `index`');
    }
}

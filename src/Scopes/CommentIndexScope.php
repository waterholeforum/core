<?php

namespace Waterhole\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

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

        $builder->select($builder->qualifyColumn('*'))->selectSub(
            DB::table('comments', 'ci')
                ->selectRaw('count(*)')
                ->whereColumn('ci.post_id', 'comments.post_id')
                ->whereColumn('ci.id', '<', 'comments.id'),
            'index',
        );
    }
}

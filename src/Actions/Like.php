<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use ReflectionClass;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\View\Components\Reactions;
use Waterhole\View\TurboStream;

class Like extends Action
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Post || $model instanceof Comment;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user &&
            $user->can(strtolower((new ReflectionClass($model))->getShortName()) . '.like', $model);
    }

    public function shouldRender(Collection $models): bool
    {
        return false;
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.reaction-like');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-thumb-up';
    }

    public function run(Collection $models)
    {
        $models->each(function ($item) {
            $item->likedBy()->toggle([request()->user()->id]);
            $item->refreshLikeMetadata()->save();
        });
    }

    public function stream(Model $model): array
    {
        return [TurboStream::replace(new Reactions($model))];
    }
}

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

class React extends Action
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Post || $model instanceof Comment;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user &&
            $user->can(
                strtolower((new ReflectionClass($model))->getShortName()) . '.react',
                $model,
            );
    }

    public function shouldRender(Collection $models, string $context = null): bool
    {
        return false;
    }

    public function label(Collection $models): string
    {
        return 'React';
    }

    public function icon(Collection $models): string
    {
        return 'tabler-mood-smile';
    }

    public function run(Collection $models)
    {
        $models->each(function (Post|Comment $item) {
            $reaction = $item->reactions()->firstOrNew([
                'user_id' => request()->user()->id,
                'reaction_type_id' => request('reaction_type_id'),
            ]);

            if ($reaction->exists) {
                $reaction->delete();
            } else {
                $reaction->save();
            }

            $item->recalculateScore()->save();
        });
    }

    public function stream(Model $model): array
    {
        $model = $model
            ->newQuery()
            ->whereKey($model->getKey())
            ->select('*')
            ->withReactions()
            ->firstOrFail();

        return [TurboStream::replace(new Reactions($model))];
    }
}

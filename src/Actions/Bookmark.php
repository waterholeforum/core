<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;

class Bookmark extends Action
{
    public function shouldRender(Collection $models, ?string $context = null): bool
    {
        return $context !== 'cp';
    }

    public function appliesTo(Model $model): bool
    {
        if (!method_exists($model, 'bookmark')) {
            return false;
        }

        return !method_exists($model, 'trashed') || !$model->trashed();
    }

    public function label(Collection $models): string
    {
        if (!$models[0]->isBookmarked()) {
            return __('waterhole::forum.save-button');
        }

        return $this->renderType() === self::TYPE_BUTTON
            ? __('waterhole::forum.saved-button')
            : __('waterhole::forum.unsave-button');
    }

    public function icon(Collection $models): string
    {
        if (!$models[0]->isBookmarked()) {
            return 'tabler-bookmark';
        }

        return $this->renderType() === self::TYPE_MENU_ITEM
            ? 'tabler-bookmark-off'
            : 'tabler-bookmark-filled';
    }

    public function attributes(Collection $models): array
    {
        return [
            'class' =>
                $models[0]->isBookmarked() && $this->renderType() !== self::TYPE_MENU_ITEM
                    ? 'color-accent'
                    : '',
        ];
    }

    public function run(Collection $models)
    {
        $userId = request()->user()->id;

        $models->each(function (Model $model) use ($userId) {
            $bookmark = $model->bookmark()->first();

            if ($bookmark) {
                $bookmark->delete();
            } else {
                $model->bookmarks()->create(['user_id' => $userId]);
            }
        });
    }
}

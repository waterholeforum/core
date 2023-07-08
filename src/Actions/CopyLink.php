<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;

/**
 * Copy Link action.
 *
 * This action applies to any model that has a URL attribute. It is a link to
 * the model's URL, progressively enhanced via Stimulus to copy the link to
 * the clipboard instead of navigating there.
 */
class CopyLink extends Link
{
    public function appliesTo($model): bool
    {
        return $model instanceof Post || $model instanceof Comment;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return true;
    }

    public function shouldRender(Collection $models, string $context = null): bool
    {
        return $context !== 'cp';
    }

    public function label(Collection $models): string
    {
        return __('waterhole::system.copy-link-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-link';
    }

    public function attributes(Collection $models): array
    {
        return [
            'data-turbo-frame' => '_top',
            'data-controller' => 'copy-link',
        ];
    }

    public function url(Model $model): string
    {
        return $model->url;
    }
}

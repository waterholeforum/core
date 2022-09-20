<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Models\User;
use Waterhole\Waterhole;

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
        return (bool) $model->url;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return true;
    }

    public function shouldRender(Collection $models): bool
    {
        return !Waterhole::isAdminRoute();
    }

    public function label(Collection $models): string
    {
        return 'Copy Link';
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

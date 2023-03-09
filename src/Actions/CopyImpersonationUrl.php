<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Waterhole\Models\Model;
use Waterhole\Models\User;

/**
 * Copy Impersonation URL action.
 *
 *
 */
class CopyImpersonationUrl extends Link
{
    public function appliesTo($model): bool
    {
        return $model instanceof User;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return (bool) $user?->isAdmin();
    }

    public function label(Collection $models): string
    {
        return __('waterhole::user.copy-impersonation-url-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-spy';
    }

    public function attributes(Collection $models): array
    {
        return [
            'data-turbo-frame' => '_top',
            'data-controller' => 'copy-link',
            'data-copy-link-message-value' => __(
                'waterhole::user.impersonation-url-copied-message',
            ),
        ];
    }

    public function url(Model $model): string
    {
        return URL::temporarySignedRoute('waterhole.impersonate', now()->addHour(), [
            'user' => $model,
        ]);
    }
}

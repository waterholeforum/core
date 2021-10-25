<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\User;

class CopyLink extends Link
{
    public function name(): string
    {
        return 'Copy Link';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-link';
    }

    public function attributes(Collection $items): array
    {
        return [
            'data-turbo-frame' => '_top',
            'data-controller' => 'copy-link',
        ];
    }

    public function appliesTo($item): bool
    {
        return (bool) $item->url;
    }

    public function authorize(?User $user, $item): bool
    {
        return true;
    }

    public function link($item)
    {
        return $item->url;
    }
}

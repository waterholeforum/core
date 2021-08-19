<?php

namespace Waterhole\Actions;

use Waterhole\Models\User;

class Edit extends Link
{
    public function name(): string
    {
        return 'Edit';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Editable;
    }

    public function authorize(User $user, $item): bool
    {
        return $user->can('update', $item);
    }

    public function link($item)
    {
        return $item->edit_url;
    }
}

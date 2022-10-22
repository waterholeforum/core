<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\FormSection;

abstract class UserForm
{
    use OrderedList, OfComponents;
}

UserForm::add(
    'account',
    fn($user) => new FormSection(
        __('waterhole::admin.user-account-title'),
        UserFormAccount::components(compact('user')),
    ),
    position: -20,
);

UserForm::add(
    'profile',
    fn($user) => new FormSection(
        __('waterhole::admin.user-profile-title'),
        UserFormProfile::components(compact('user')),
        open: false,
    ),
    position: -10,
);

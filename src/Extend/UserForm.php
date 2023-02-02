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
    fn($model) => new FormSection(
        __('waterhole::admin.user-account-title'),
        UserFormAccount::components(compact('model')),
    ),
    position: -20,
    key: 'account',
);

UserForm::add(
    fn($model) => new FormSection(
        __('waterhole::admin.user-profile-title'),
        UserFormProfile::components(compact('model')),
        open: false,
    ),
    position: -10,
    key: 'profile',
);

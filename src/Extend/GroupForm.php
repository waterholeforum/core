<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\GroupPermissions;
use Waterhole\Forms\FormSection;

abstract class GroupForm
{
    use OrderedList, OfComponents;
}

GroupForm::add(
    fn($model) => new FormSection(
        __('waterhole::cp.group-details-title'),
        GroupFormDetails::components(compact('model')),
    ),
    position: -20,
    key: 'details',
);

GroupForm::add(
    fn($model) => new FormSection(
        __('waterhole::cp.group-permissions-title'),
        [new GroupPermissions($model)],
        open: false,
    ),
    position: -10,
    key: 'permissions',
);

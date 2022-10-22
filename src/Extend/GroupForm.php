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
    'details',
    fn($group) => new FormSection(
        __('waterhole::admin.group-details-title'),
        GroupFormDetails::components(compact('group')),
    ),
    position: -20,
);

GroupForm::add(
    'permissions',
    fn($group) => new FormSection(
        __('waterhole::admin.group-permissions-title'),
        [new GroupPermissions($group)],
        open: false,
    ),
    position: -10,
);

<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\Permissions;
use Waterhole\Forms\FormSection;

abstract class StructureLinkForm
{
    use OrderedList, OfComponents;
}

StructureLinkForm::add(
    fn($model) => new FormSection(
        __('waterhole::cp.link-details-title'),
        StructureLinkFormDetails::components(compact('model')),
    ),
    position: -20,
    key: 'details',
);

StructureLinkForm::add(
    fn($model) => new FormSection(
        __('waterhole::cp.link-permissions-title'),
        [new Permissions($model)],
        open: false,
    ),
    position: -10,
    key: 'permissions',
);

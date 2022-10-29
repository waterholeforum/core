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
    'details',
    fn($model) => new FormSection(
        __('waterhole::admin.link-details-title'),
        StructureLinkFormDetails::components(compact('model')),
    ),
    position: -20,
);

StructureLinkForm::add(
    'permissions',
    fn($model) => new FormSection(
        __('waterhole::admin.link-permissions-title'),
        [new Permissions($model)],
        open: false,
    ),
    position: -10,
);

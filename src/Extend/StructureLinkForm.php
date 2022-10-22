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
    fn($link) => new FormSection(
        __('waterhole::admin.link-details-title'),
        StructureLinkFormDetails::components(compact('link')),
    ),
    position: -20,
);

StructureLinkForm::add(
    'permissions',
    fn($link) => new FormSection(
        __('waterhole::admin.link-permissions-title'),
        [new Permissions($link)],
        open: false,
    ),
    position: -10,
);

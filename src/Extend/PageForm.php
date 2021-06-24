<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\Permissions;
use Waterhole\Forms\FormSection;

abstract class PageForm
{
    use OrderedList, OfComponents;
}

PageForm::add(
    fn($model) => new FormSection(
        __('waterhole::admin.page-details-title'),
        PageFormDetails::components(compact('model')),
    ),
    position: -20,
    key: 'details',
);

PageForm::add(
    fn($model) => new FormSection(
        __('waterhole::admin.page-permissions-title'),
        [new Permissions($model)],
        open: false,
    ),
    position: -10,
    key: 'permissions',
);

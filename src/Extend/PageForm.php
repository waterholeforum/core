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
    'details',
    fn($page) => new FormSection(
        __('waterhole::admin.page-details-title'),
        PageFormDetails::components(compact('page')),
    ),
    position: -20,
);

PageForm::add(
    'permissions',
    fn($page) => new FormSection(
        __('waterhole::admin.page-permissions-title'),
        [new Permissions($page)],
        open: false,
    ),
    position: -10,
);

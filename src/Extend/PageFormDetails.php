<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\Icon;
use Waterhole\Forms\Fields\PageBody;
use Waterhole\Forms\Fields\PageName;
use Waterhole\Forms\Fields\PageSlug;

abstract class PageFormDetails
{
    use OrderedList, OfComponents;
}

PageFormDetails::add(PageName::class, 0, 'name');
PageFormDetails::add(PageSlug::class, 0, 'slug');
PageFormDetails::add(Icon::class, 0, 'icon');
PageFormDetails::add(PageBody::class, 0, 'body');

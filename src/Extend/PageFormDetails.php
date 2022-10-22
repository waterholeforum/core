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

PageFormDetails::add('name', PageName::class);
PageFormDetails::add('slug', PageSlug::class);
PageFormDetails::add('icon', Icon::class);
PageFormDetails::add('body', PageBody::class);

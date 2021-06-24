<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\Icon;
use Waterhole\Forms\Fields\StructureLinkName;
use Waterhole\Forms\Fields\StructureLinkUrl;

abstract class StructureLinkFormDetails
{
    use OrderedList, OfComponents;
}

StructureLinkFormDetails::add(StructureLinkName::class, 0, 'name');
StructureLinkFormDetails::add(Icon::class, 0, 'icon');
StructureLinkFormDetails::add(StructureLinkUrl::class, 0, 'url');

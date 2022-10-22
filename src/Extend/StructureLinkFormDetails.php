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

StructureLinkFormDetails::add('name', StructureLinkName::class);
StructureLinkFormDetails::add('icon', Icon::class);
StructureLinkFormDetails::add('url', StructureLinkUrl::class);

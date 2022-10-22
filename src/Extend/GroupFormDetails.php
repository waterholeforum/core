<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\GroupAppearance;
use Waterhole\Forms\Fields\GroupName;

abstract class GroupFormDetails
{
    use OrderedList, OfComponents;
}

GroupFormDetails::add('name', GroupName::class);
GroupFormDetails::add('appearance', GroupAppearance::class);

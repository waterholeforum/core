<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\TagName;

abstract class TagForm
{
    use OrderedList, OfComponents;
}

TagForm::add('name', TagName::class);

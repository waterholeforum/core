<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\ReactionSetDefaults;
use Waterhole\Forms\Fields\ReactionSetName;

abstract class ReactionSetForm
{
    use OrderedList, OfComponents;
}

ReactionSetForm::add(ReactionSetName::class, 0, 'name');
ReactionSetForm::add(ReactionSetDefaults::class, 0, 'defaults');

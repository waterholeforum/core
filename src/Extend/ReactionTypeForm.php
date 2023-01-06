<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\Icon;
use Waterhole\Forms\Fields\ReactionTypeName;
use Waterhole\Forms\Fields\ReactionTypeScore;

abstract class ReactionTypeForm
{
    use OrderedList, OfComponents;
}

ReactionTypeForm::add('name', ReactionTypeName::class);
ReactionTypeForm::add('icon', Icon::class);
ReactionTypeForm::add('score', ReactionTypeScore::class);

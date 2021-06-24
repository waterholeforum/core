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

ReactionTypeForm::add(ReactionTypeName::class, 0, 'name');
ReactionTypeForm::add(Icon::class, 0, 'icon');
ReactionTypeForm::add(ReactionTypeScore::class, 0, 'score');

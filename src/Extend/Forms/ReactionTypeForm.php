<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\Icon;
use Waterhole\Forms\Fields\ReactionTypeName;
use Waterhole\Forms\Fields\ReactionTypeScore;

/**
 * List of fields for the reaction type create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class ReactionTypeForm extends ComponentList
{
    public function __construct()
    {
        $this->add('name', ReactionTypeName::class);
        $this->add('icon', Icon::class);
        $this->add('score', ReactionTypeScore::class);
    }
}

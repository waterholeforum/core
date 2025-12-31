<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\ReactionSetDefaults;
use Waterhole\Forms\Fields\ReactionSetName;

/**
 * List of fields for the reaction set create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class ReactionSetForm extends ComponentList
{
    public function __construct()
    {
        $this->add(ReactionSetName::class, 'name');
        $this->add(ReactionSetDefaults::class, 'defaults');
    }
}

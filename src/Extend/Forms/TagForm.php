<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\TagName;

/**
 * List of fields for the tag create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class TagForm extends ComponentList
{
    public function __construct()
    {
        $this->add('name', TagName::class);
    }
}

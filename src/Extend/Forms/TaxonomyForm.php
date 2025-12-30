<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\TaxonomyName;
use Waterhole\Forms\Fields\TaxonomyOptions;

/**
 * List of fields for the taxonomy create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class TaxonomyForm extends ComponentList
{
    public function __construct()
    {
        $this->add('name', TaxonomyName::class);
        $this->add('options', TaxonomyOptions::class);
    }
}

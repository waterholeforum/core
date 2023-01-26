<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\TaxonomyName;
use Waterhole\Forms\Fields\TaxonomyOptions;

abstract class TaxonomyForm
{
    use OrderedList, OfComponents;
}

TaxonomyForm::add('name', TaxonomyName::class);
TaxonomyForm::add('options', TaxonomyOptions::class);

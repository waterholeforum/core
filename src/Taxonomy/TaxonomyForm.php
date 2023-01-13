<?php

namespace Waterhole\Taxonomy;

use Waterhole\Extend;
use Waterhole\Forms\Fields\Permissions;
use Waterhole\Forms\Form;
use Waterhole\Forms\FormSection;

class TaxonomyForm extends Form
{
    public function __construct(Taxonomy $taxonomy)
    {
        parent::__construct($taxonomy);
    }

    public function fields(): array
    {
        return [
            new FormSection(
                __('waterhole::admin.taxonomy-details-title'),
                Extend\TaxonomyForm::components(['model' => $this->model]),
            ),
            new FormSection(
                __('waterhole::admin.taxonomy-permissions-title'),
                [new Permissions($this->model)],
                open: false,
            ),
        ];
    }
}

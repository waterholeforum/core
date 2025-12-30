<?php

namespace Waterhole\Forms;

use Waterhole\Forms\Fields\Permissions;
use Waterhole\Models\Taxonomy;

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
                __('waterhole::cp.taxonomy-details-title'),
                resolve(\Waterhole\Extend\Forms\TaxonomyForm::class)->components(['model' => $this->model]),
            ),
            new FormSection(
                __('waterhole::cp.taxonomy-permissions-title'),
                [new Permissions($this->model)],
                open: false,
            ),
        ];
    }
}

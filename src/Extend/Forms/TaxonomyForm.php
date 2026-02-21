<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\TaxonomyTags;
use Waterhole\Forms\Fields\TaxonomyName;
use Waterhole\Forms\Fields\TaxonomyOptions;
use Waterhole\Forms\FormSection;

/**
 * List of fields for the taxonomy create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class TaxonomyForm extends ComponentList
{
    public ComponentList $details;
    public ComponentList $tags;

    public function __construct()
    {
        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.taxonomy-details-title'),
                $this->details->components(compact('model')),
            ),
            'details',
        );

        $this->details = (new ComponentList())
            ->add(TaxonomyName::class, 'name')
            ->add(TaxonomyOptions::class, 'options');

        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.taxonomy-tags-title'),
                $this->tags->components(compact('model')),
                open: false,
            ),
            'tags',
        );

        $this->tags = (new ComponentList())->add(TaxonomyTags::class, 'tags');
    }
}

<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\Icon;
use Waterhole\Forms\Fields\PageBody;
use Waterhole\Forms\Fields\PageSlug;
use Waterhole\Forms\Fields\Permissions;
use Waterhole\Forms\Fields\StructureDescription;
use Waterhole\Forms\Fields\StructureListing;
use Waterhole\Forms\Fields\StructureName;
use Waterhole\Forms\FormSection;

/**
 * List of fields for the page create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class PageForm extends ComponentList
{
    public ComponentList $details;

    public function __construct()
    {
        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.page-details-title'),
                $this->details->components(compact('model')),
            ),
            'details',
        );

        $this->details = (new ComponentList())
            ->add(StructureName::class, 'name')
            ->add(PageSlug::class, 'slug')
            ->add(Icon::class, 'icon')
            ->add(StructureDescription::class, 'description')
            ->add(PageBody::class, 'body');

        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.page-permissions-title'),
                [new Permissions($model), new StructureListing($model)],
                open: false,
            ),
            'permissions',
        );
    }
}

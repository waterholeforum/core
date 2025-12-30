<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\Icon;
use Waterhole\Forms\Fields\Permissions;
use Waterhole\Forms\Fields\StructureLinkName;
use Waterhole\Forms\Fields\StructureLinkUrl;
use Waterhole\Forms\FormSection;

/**
 * List of fields for the structure link create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class StructureLinkForm extends ComponentList
{
    public ComponentList $details;

    public function __construct()
    {
        $this->add(
            'details',
            fn($model) => new FormSection(
                __('waterhole::cp.link-details-title'),
                $this->details->components(compact('model')),
            ),
        );

        $this->details = (new ComponentList())
            ->add('name', StructureLinkName::class)
            ->add('icon', Icon::class)
            ->add('url', StructureLinkUrl::class);

        $this->add(
            'permissions',
            fn($model) => new FormSection(
                __('waterhole::cp.link-permissions-title'),
                [new Permissions($model)],
                open: false,
            ),
        );
    }
}

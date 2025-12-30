<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\GroupAppearance;
use Waterhole\Forms\Fields\GroupName;
use Waterhole\Forms\Fields\GroupPermissions;
use Waterhole\Forms\FormSection;

/**
 * List of fields for the group create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class GroupForm extends ComponentList
{
    public ComponentList $details;

    public function __construct()
    {
        $this->add(
            'details',
            fn($model) => new FormSection(
                __('waterhole::cp.group-details-title'),
                $this->details->components(compact('model')),
            ),
        );

        $this->details = (new ComponentList())
            ->add('name', GroupName::class)
            ->add('appearance', GroupAppearance::class);

        $this->add(
            'permissions',
            fn($model) => new FormSection(
                __('waterhole::cp.group-permissions-title'),
                [new GroupPermissions($model)],
                open: false,
            ),
        );
    }
}

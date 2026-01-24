<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\GroupAppearance;
use Waterhole\Forms\Fields\GroupChannelPermissions;
use Waterhole\Forms\Fields\GroupGlobalPermissions;
use Waterhole\Forms\Fields\GroupName;
use Waterhole\Forms\Fields\GroupRules;
use Waterhole\Forms\FormSection;

/**
 * List of fields for the group create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class GroupForm extends ComponentList
{
    public ComponentList $details;
    public ComponentList $permissions;

    public function __construct()
    {
        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.group-details-title'),
                $this->details->components(compact('model')),
            ),
            'details',
        );

        $this->details = (new ComponentList())
            ->add(GroupName::class, 'name')
            ->add(GroupAppearance::class, 'appearance')
            ->add(GroupRules::class, 'rules');

        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.group-permissions-title'),
                $this->permissions->components(compact('model')),
                open: false,
            ),
            'permissions',
        );

        $this->permissions = (new ComponentList())
            ->add(GroupGlobalPermissions::class, 'global')
            ->add(GroupChannelPermissions::class, 'channel');
    }
}

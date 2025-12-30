<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\Icon;
use Waterhole\Forms\Fields\PageBody;
use Waterhole\Forms\Fields\PageName;
use Waterhole\Forms\Fields\PageSlug;
use Waterhole\Forms\Fields\Permissions;
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
            'details',
            fn($model) => new FormSection(
                __('waterhole::cp.page-details-title'),
                $this->details->components(compact('model')),
            ),
        );

        $this->details = (new ComponentList())
            ->add('name', PageName::class)
            ->add('slug', PageSlug::class)
            ->add('icon', Icon::class)
            ->add('body', PageBody::class);

        $this->add(
            'permissions',
            fn($model) => new FormSection(
                __('waterhole::cp.page-permissions-title'),
                [new Permissions($model)],
                open: false,
            ),
        );
    }
}

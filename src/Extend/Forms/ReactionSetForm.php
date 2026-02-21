<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\ReactionTypes;
use Waterhole\Forms\Fields\ReactionSetDefaults;
use Waterhole\Forms\Fields\ReactionSetName;
use Waterhole\Forms\FormSection;

/**
 * List of fields for the reaction set create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class ReactionSetForm extends ComponentList
{
    public ComponentList $details;
    public ComponentList $reactionTypes;

    public function __construct()
    {
        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.reaction-set-details-title'),
                $this->details->components(compact('model')),
            ),
            'details',
        );

        $this->details = (new ComponentList())
            ->add(ReactionSetName::class, 'name')
            ->add(ReactionSetDefaults::class, 'defaults');

        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.reaction-types-title'),
                $this->reactionTypes->components(compact('model')),
                open: false,
            ),
            'reaction-types',
        );

        $this->reactionTypes = (new ComponentList())->add(ReactionTypes::class, 'reaction-types');
    }
}

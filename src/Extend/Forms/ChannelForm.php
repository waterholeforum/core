<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\ChannelAnswers;
use Waterhole\Forms\Fields\ChannelApproval;
use Waterhole\Forms\Fields\ChannelDescription;
use Waterhole\Forms\Fields\ChannelFilters;
use Waterhole\Forms\Fields\ChannelIgnore;
use Waterhole\Forms\Fields\ChannelInstructions;
use Waterhole\Forms\Fields\ChannelLayout;
use Waterhole\Forms\Fields\ChannelName;
use Waterhole\Forms\Fields\ChannelReactions;
use Waterhole\Forms\Fields\ChannelSimilarPosts;
use Waterhole\Forms\Fields\ChannelSlug;
use Waterhole\Forms\Fields\ChannelTaxonomies;
use Waterhole\Forms\Fields\Icon;
use Waterhole\Forms\Fields\Permissions;
use Waterhole\Forms\FormSection;

/**
 * List of fields for the channel create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class ChannelForm extends ComponentList
{
    public ComponentList $details;
    public ComponentList $features;
    public ComponentList $layout;
    public ComponentList $posting;
    public ComponentList $permissions;

    public function __construct()
    {
        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.channel-details-title'),
                $this->details->components(compact('model')),
            ),
            'details',
        );

        $this->details = (new ComponentList())
            ->add(ChannelName::class, 'name')
            ->add(ChannelSlug::class, 'slug')
            ->add(Icon::class, 'icon')
            ->add(ChannelDescription::class, 'description')
            ->add(ChannelIgnore::class, 'ignore');

        $this->add(
            fn(...$args) => new FormSection(
                __('waterhole::cp.channel-features-title'),
                $this->features->components($args),
                open: false,
            ),
            'features',
        );

        $this->features = (new ComponentList())
            ->add(ChannelTaxonomies::class, 'taxonomies')
            ->add(ChannelAnswers::class, 'answers')
            ->add(ChannelReactions::class, 'reactions');

        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.channel-layout-title'),
                $this->layout->components(compact('model')),
                open: false,
            ),
            'layout',
        );

        $this->layout = (new ComponentList())
            ->add(ChannelLayout::class, 'layout')
            ->add(ChannelFilters::class, 'filters');

        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.channel-posting-title'),
                $this->posting->components(compact('model')),
                open: false,
            ),
            'posting',
        );

        $this->posting = (new ComponentList())
            ->add(ChannelInstructions::class, 'instructions')
            ->add(ChannelSimilarPosts::class, 'similar-posts');

        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.channel-permissions-title'),
                $this->permissions->components(compact('model')),
                open: false,
            ),
            'permissions',
        );

        $this->permissions = (new ComponentList())
            ->add(Permissions::class, 'permissions')
            ->add(ChannelApproval::class, 'approval');
    }
}

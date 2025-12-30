<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\ChannelAnswers;
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

    public function __construct()
    {
        $this->add(
            'details',
            fn($model) => new FormSection(
                __('waterhole::cp.channel-details-title'),
                $this->details->components(compact('model')),
            ),
        );

        $this->details = (new ComponentList())
            ->add('name', ChannelName::class)
            ->add('slug', ChannelSlug::class)
            ->add('icon', Icon::class)
            ->add('description', ChannelDescription::class)
            ->add('ignore', ChannelIgnore::class);

        $this->add(
            'features',
            fn(...$args) => new FormSection(
                __('waterhole::cp.channel-features-title'),
                $this->features->components($args),
                open: false,
            ),
        );

        $this->features = (new ComponentList())
            ->add('taxonomies', ChannelTaxonomies::class)
            ->add('answers', ChannelAnswers::class)
            ->add('reactions', ChannelReactions::class);

        $this->add(
            'layout',
            fn($model) => new FormSection(
                __('waterhole::cp.channel-layout-title'),
                $this->layout->components(compact('model')),
                open: false,
            ),
        );

        $this->layout = (new ComponentList())
            ->add('layout', ChannelLayout::class)
            ->add('filters', ChannelFilters::class);

        $this->add(
            'posting',
            fn($model) => new FormSection(
                __('waterhole::cp.channel-posting-title'),
                $this->posting->components(compact('model')),
                open: false,
            ),
        );

        $this->posting = (new ComponentList())
            ->add('instructions', ChannelInstructions::class)
            ->add('similar-posts', ChannelSimilarPosts::class);

        $this->add(
            'permissions',
            fn($model) => new FormSection(
                __('waterhole::cp.channel-permissions-title'),
                [new Permissions($model)],
                open: false,
            ),
        );
    }
}

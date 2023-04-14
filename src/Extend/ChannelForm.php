<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\Permissions;
use Waterhole\Forms\FormSection;

abstract class ChannelForm
{
    use OrderedList, OfComponents;
}

ChannelForm::add(
    fn($model) => new FormSection(
        __('waterhole::cp.channel-details-title'),
        ChannelFormDetails::components(compact('model')),
    ),
    position: -100,
    key: 'details',
);

ChannelForm::add(
    fn($model) => new FormSection(
        __('waterhole::cp.channel-features-title'),
        ChannelFormFeatures::components(compact('model')),
        open: false,
    ),
    position: -90,
    key: 'features',
);

ChannelForm::add(
    fn($model) => new FormSection(
        __('waterhole::cp.channel-layout-title'),
        ChannelFormLayout::components(compact('model')),
        open: false,
    ),
    position: -80,
    key: 'layout',
);

ChannelForm::add(
    fn($model) => new FormSection(
        __('waterhole::cp.channel-posting-title'),
        ChannelFormPosting::components(compact('model')),
        open: false,
    ),
    position: -70,
    key: 'posting',
);

ChannelForm::add(
    fn($model) => new FormSection(
        __('waterhole::cp.channel-permissions-title'),
        [new Permissions($model)],
        open: false,
    ),
    position: -60,
    key: 'permissions',
);

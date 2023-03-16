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
        __('waterhole::admin.channel-details-title'),
        ChannelFormDetails::components(compact('model')),
    ),
    position: -100,
    key: 'details',
);

ChannelForm::add(
    fn($model) => new FormSection(
        __('waterhole::admin.channel-options-title'),
        ChannelFormOptions::components(compact('model')),
        open: false,
    ),
    position: -90,
    key: 'options',
);

ChannelForm::add(
    fn($model) => new FormSection(
        __('waterhole::admin.channel-posting-title'),
        ChannelFormPosting::components(compact('model')),
        open: false,
    ),
    position: -80,
    key: 'posting',
);

ChannelForm::add(
    fn($model) => new FormSection(
        __('waterhole::admin.channel-permissions-title'),
        [new Permissions($model)],
        open: false,
    ),
    position: -70,
    key: 'permissions',
);

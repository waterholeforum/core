<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\Permissions;
use Waterhole\Forms\FormSection;

class ChannelForm
{
    use OrderedList, OfComponents;
}

ChannelForm::add(
    'details',
    fn($channel) => new FormSection(
        __('waterhole::admin.channel-details-title'),
        ChannelFormDetails::components(compact('channel')),
    ),
    position: -30,
);

ChannelForm::add(
    'options',
    fn($channel) => new FormSection(
        __('waterhole::admin.channel-options-title'),
        ChannelFormOptions::components(compact('channel')),
        open: false,
    ),
    position: -20,
);

ChannelForm::add(
    'permissions',
    fn($channel) => new FormSection(
        __('waterhole::admin.channel-permissions-title'),
        [new Permissions($channel)],
        open: false,
    ),
    position: -10,
);

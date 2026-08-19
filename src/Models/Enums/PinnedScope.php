<?php

namespace Waterhole\Models\Enums;

enum PinnedScope: string
{
    case Channel = 'channel';
    case Global = 'global';
}

<?php

declare(strict_types=1);

namespace Waterhole\Models\Enums;

enum PinnedScope: string
{
    case Channel = 'channel';
    case Global = 'global';
}

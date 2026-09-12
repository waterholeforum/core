<?php

declare(strict_types=1);

namespace Waterhole\Models\Enums;

enum Mentionable: string
{
    case Moderators = 'moderators';
    case Members = 'members';
    case Anyone = 'anyone';
}

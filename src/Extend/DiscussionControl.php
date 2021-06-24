<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\View\Components\CategorySelectDialog;

class DiscussionControl
{
    use ManagesComponents;

    const POSITION_START = -10;
    const POSITION_USER = 0;
    const POSITION_MODERATION = 10;
    const POSITION_END = 20;

    protected static function defaultComponents(): array
    {
        return [
            'components.DiscussionControlSubscription' => static::POSITION_USER,
            'components.DropdownDivider' => static::POSITION_MODERATION - 1,
            'components.DiscussionControlRename' => static::POSITION_MODERATION,
            'components.DiscussionControlChangeCategory' => static::POSITION_MODERATION,
            'components.DiscussionControlLock' => static::POSITION_MODERATION,
            'components.DiscussionControlDelete' => static::POSITION_END,
        ];
    }
}

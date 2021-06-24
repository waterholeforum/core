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

class DiscussionBadge
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'components.DiscussionBadgeTrash' => 0,
            'components.DiscussionBadgeSubscription' => 0,
            'components.DiscussionBadgeDraft' => 0,
            'components.DiscussionBadgeLocked' => 0,
        ];
    }
}

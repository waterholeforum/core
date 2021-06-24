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

use Waterhole\Extend\Concerns\ManagesItems;

class DiscussionListRoute
{
    use ManagesItems;

    protected static function defaultItems(): array
    {
        return [
            'trash' => ['filter' => ['trashed' => 1]],
            'subscribed' => ['filter' => ['subscribed' => 1]],
            'muted' => ['filter' => ['muted' => 1]],
        ];
    }
}

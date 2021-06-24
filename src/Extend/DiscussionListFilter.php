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

use DateTime;
use Waterhole\Extend\Concerns\ManagesItems;

class DiscussionListFilter
{
    use ManagesItems;

    protected static function defaultItems(): array
    {
        return [
            'latest' => ['sort' => '-lastCommentAt'],
            'new' => ['sort' => '-createdAt'],
            'unread' => ['filter' => ['unread' => 1], 'sort' => '-lastCommentAt'],
            'top' => ['sort' => '-commentCount'],
            'top-year' => ['sort' => '-commentCount', 'filter' => ['createdAt' => '>='.(new DateTime('-1 year'))->format('Y-m-d')]],
            'top-month' => ['sort' => '-commentCount', 'filter' => ['createdAt' => '>='.(new DateTime('-1 month'))->format('Y-m-d')]],
            'top-week' => ['sort' => '-commentCount', 'filter' => ['createdAt' => '>='.(new DateTime('-1 week'))->format('Y-m-d')]],
            'top-day' => ['sort' => '-commentCount', 'filter' => ['createdAt' => '>='.(new DateTime('-1 day'))->format('Y-m-d')]],
        ];
    }
}

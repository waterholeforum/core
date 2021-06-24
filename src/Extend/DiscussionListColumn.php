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

class DiscussionListColumn
{
    use ManagesComponents;

    const BEFORE_AUTHOR = -20;
    const AFTER_AUTHOR = -10;
    const AFTER_CONTENT = 0;
    const AFTER_LIKES = 10;
    const AFTER_REPLIES = 20;
    const AFTER_LAST_POST = 30;

    protected static function defaultComponents(): array
    {
        return [
            'components.DiscussionListColumnAuthor' => self::AFTER_AUTHOR,
            'components.DiscussionListColumnContent' => self::AFTER_CONTENT,
            'components.DiscussionListColumnLikes' => self::AFTER_LIKES,
            'components.DiscussionListColumnReplies' => self::AFTER_REPLIES,
            'components.DiscussionListColumnLastPost' => self::AFTER_LAST_POST,
        ];
    }
}

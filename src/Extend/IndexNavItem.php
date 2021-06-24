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

class IndexNavItem
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'components.IndexNavItemAllDiscussions' => 0,
            'components.IndexNavItemCategories' => 0,
            'components.IndexNavItemInbox' => 0,
            'components.IndexNavItemDrafts' => 0,
            'components.IndexNavItemSubscribed' => 0,
            'components.IndexNavItemTrash' => 0,
            'components.IndexNavItemCategoryList' => 0,
            'components.IndexNavItemPages' => 0,
        ];
    }
}

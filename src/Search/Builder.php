<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Search;

use Laravel\Scout\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    public $group;

    public function groupBy($field)
    {
        $this->group = $field;

        return $this;
    }
}

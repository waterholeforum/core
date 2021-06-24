<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Support;

use DateTime;

class TimeAgo
{
    const MINUTE = 60;
    const HOUR = self::MINUTE * 60;
    const DAY = self::HOUR * 24;
    const WEEK = self::DAY * 7;
    const MONTH = self::DAY * 30;
    const YEAR = self::DAY * 365;

    public static function calculate($then)
    {
        if (! $then instanceof DateTime) {
            $then = new DateTime($then);
        }

        $now = new DateTime();
        $seconds = abs($now->getTimestamp() - $then->getTimestamp());
        $suffix = $then < $now ? 'ago' : 'from now';

        if ($seconds < static::MINUTE) {
            [$value, $unit] = [$seconds, 'second'];
        } elseif ($seconds < static::HOUR) {
            [$value, $unit] = [round($seconds / static::MINUTE), 'minute'];
        } elseif ($seconds < static::DAY) {
            [$value, $unit] = [round($seconds / static::HOUR), 'hour'];
        } elseif ($seconds < static::WEEK) {
            [$value, $unit] = [round($seconds / static::DAY), 'day'];
        } elseif ($seconds < static::MONTH) {
            [$value, $unit] = [round($seconds / static::WEEK), 'week'];
        } elseif ($seconds < static::YEAR) {
            [$value, $unit] = [round($seconds / static::MONTH), 'month'];
        } else {
            [$value, $unit] = [round($seconds / static::YEAR), 'year'];
        }

        return compact('value', 'unit', 'suffix') + ['date' => $then];
    }
}

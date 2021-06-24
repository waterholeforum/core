<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Locale;

use Tobyz\Fluent\Scope;
use Tobyz\Fluent\Types\Number;

class ShortNumber extends Number
{
    public function toString(Scope $scope)
    {
        if ($this->value >= 1000) {
            $n = $this->value;
            $key = 'short-number-1'.str_repeat('0', floor(log10($n)));

            if ($message = $scope->bundle->getMessage($key)) {
                $format = $message['value'];
                [$number, $unit] = str_split($format, strrpos($format, '0') + 1);
                $split = explode('.', $number);
                $digits = strlen($split[0]);
                $fractionDigits = count($split) > 1 ? strlen($split[1]) : 0;
                $threshold = pow(10, $digits);

                while ($n >= $threshold) {
                    $n /= 10;
                }

                $formattedNumber = (new Number($n, [
                    'maximumFractionDigits' => $fractionDigits
                ]))->toString($scope);

                return $formattedNumber.$unit;
            }
        }

        return parent::toString($scope);
    }
}

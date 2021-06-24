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

use Waterhole\Application;
use Illuminate\Contracts\Auth\Access\Gate as LaravelGate;

class Gate
{
    private $ability;
    private $callback;

    public function __construct(string $ability, callable $callback)
    {
        $this->ability = $ability;
        $this->callback = $callback;
    }

    public function boot(Application $app)
    {
        $app->make(LaravelGate::class)->before(function (?User $user, string $ability, array $arguments) {
            if ($ability !== $this->ability) {
                return null;
            }

            if (($return = $this->callback($user, ...$arguments)) !== null) {
                return $return;
            }
        });
    }
}

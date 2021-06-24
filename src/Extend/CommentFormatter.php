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
use Waterhole\Formatter\Formatter;

class CommentFormatter
{
    private $configure = [];
    private $parsing = [];
    private $rendering = [];

    public function configure(callable $callback)
    {
        $this->configure[] = $callback;

        return $this;
    }

    public function parsing(callable $callback)
    {
        $this->parsing[] = $callback;

        return $this;
    }

    public function rendering(callable $callback)
    {
        $this->rendering[] = $callback;

        return $this;
    }

    public function register(Application $app)
    {
        $app->resolving('waterhole.formatter.comments', function (Formatter $formatter) {
            $formatter->configure(function ($config) {
                foreach ($this->configure as $callback) {
                    $callback($config);
                }
            });

            $formatter->parsing(function ($parser, $context) {
                foreach ($this->parsing as $callback) {
                    $callback($parser, $context['post'] ?? null);
                }
            });

            $formatter->rendering(function ($parser, $context) {
                foreach ($this->rendering as $callback) {
                    $callback($parser, $context['post'] ?? null, $context['actor'] ?? null);
                }
            });
        });
    }

    public function onEnable(Application $app)
    {
        $app->make('waterhole.formatter.comments')->flush();
    }

    public function onDisable(Application $app)
    {
        $app->make('waterhole.formatter.comments')->flush();
    }
}

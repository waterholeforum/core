<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Models;

use Waterhole\Application;
use function base_path;

class Extension
{
    private string $name;
    private ?array $extenders = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return base_path('/vendor/'.$this->name);
    }

    public function getExtenders(): array
    {
        if ($this->extenders === null) {
            $this->extenders = require $this->getPath().'/extend.php';
        }

        return $this->extenders;
    }

    public function extend(Application $app, string $method)
    {
        foreach ($this->getExtenders() as $extender) {
            if (method_exists($extender, $method)) {
                $extender->$method($app, $this);
            }
        }
    }
}

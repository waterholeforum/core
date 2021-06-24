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

use Closure;
use Waterhole\Application;
use Waterhole\Frontend\Asset;
use Waterhole\Frontend\Compiler\CompilerInterface;
use Waterhole\Models\Extension;

class ForumJs
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function register(Application $app, Extension $extension = null)
    {
        static $anonymous = 1;

        $name = $extension ? $extension->getName() : 'anonymous'.$anonymous++;

        $app->resolving('waterhole.assets.forum.js', function (Asset $asset) use ($name) {
            $asset->addSources(function (CompilerInterface $compiler) use ($name) {
                $compiler->addString(function () {
                    return 'var module = {}';
                });

                if ($this->path instanceof Closure) {
                    $compiler->addString($this->path);
                } else {
                    $compiler->addFile($this->path);
                }

                $compiler->addString(function () use ($name) {
                    return "waterhole.extensions['$name']=module.exports";
                });
            });
        });
    }

    public function enable(Application $app)
    {
        $app->make('waterhole.assets.forum.js')->flush();
    }
}

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
use Waterhole\Locale\LocaleRegistry;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Locale
{
    private string $locale;
    private string $directory;
    private string $name;

    public function __construct(string $locale, string $directory, string $name)
    {
        $this->locale = $locale;
        $this->directory = $directory;
        $this->name = $name;
    }

    public function register(Application $app)
    {
        $app->resolving(LocaleRegistry::class, function (LocaleRegistry $locales) {
            $directory = new RecursiveDirectoryIterator($this->directory);
            $iterator = new RecursiveIteratorIterator($directory);
            $regex = new RegexIterator($iterator, '/^.+\.ftl$/i', RecursiveRegexIterator::GET_MATCH);

            foreach ($regex as $file) {
                $locales->addTranslations($this->locale, $file[0]);
            }

            if ($this->name) {
                $locales->addLocale($this->locale, $this->name);
            }
        });
    }
}

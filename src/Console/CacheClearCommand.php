<?php

namespace Waterhole\Console;

use Illuminate\Console\Command;
use Waterhole\Extend\Assets\Script;
use Waterhole\Extend\Assets\Stylesheet;
use Waterhole\Translation\FluentTranslator;

class CacheClearCommand extends Command
{
    protected $signature = 'waterhole:cache:clear';

    protected $description = 'Clear Waterhole caches';

    public function handle()
    {
        app('waterhole.formatter')->flush();
        app('waterhole.formatter.emoji')->flush();

        app(FluentTranslator::class)->flush();

        app(Script::class)->flush();
        app(Stylesheet::class)->flush();

        $this->info('Waterhole caches cleared!');
    }
}

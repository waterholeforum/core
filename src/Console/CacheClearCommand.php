<?php

namespace Waterhole\Console;

use Illuminate\Console\Command;
use Waterhole\Extend;
use Waterhole\Formatter\Formatter;

class CacheClearCommand extends Command
{
    protected $signature = 'waterhole:cache:clear';

    protected $description = 'Clear Waterhole caches';

    public function __construct(protected Formatter $formatter)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->formatter->flush();

        Extend\Script::flush();
        Extend\Stylesheet::flush();

        $this->info('Waterhole caches cleared!');
    }
}

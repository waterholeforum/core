<?php

namespace Waterhole\Console;

use Illuminate\Console\Command;
use Waterhole\Extend;
use Waterhole\Formatter\Formatter;
use Waterhole\Translation\FluentTranslator;

class CacheClearCommand extends Command
{
    protected $signature = 'waterhole:cache:clear';

    protected $description = 'Clear Waterhole caches';

    public function __construct(
        protected Formatter $formatter,
        protected FluentTranslator $translator,
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->formatter->flush();
        $this->translator->flush();

        Extend\Script::flush();
        Extend\Stylesheet::flush();

        $this->info('Waterhole caches cleared!');
    }
}

<?php

namespace Waterhole\Console;

use Illuminate\Console\Command;
use Waterhole\Console\Concerns\ValidatesInput;
use Waterhole\Formatter\Formatter;

class FormatterClearCommand extends Command
{
    use ValidatesInput;

    protected $signature = 'waterhole:formatter:clear';

    protected $description = 'Clear the Waterhole formatter cache';

    public function __construct(protected Formatter $formatter)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->formatter->flush();

        $this->info('Formatter cache cleared!');
    }
}

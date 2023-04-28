<?php

namespace Waterhole\Mail;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Mail\Markdown as BaseMarkdown;

class Markdown extends BaseMarkdown
{
    public function __construct(ViewFactory $view)
    {
        parent::__construct($view, [
            'theme' => 'default',
            'paths' => [__DIR__ . '/../../resources/views/mail'],
        ]);
    }
}

<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Extend\Locales;

class IndexFooterLanguage extends Component
{
    public array $locales;

    public string $currentLocale;

    public function __construct()
    {
        $this->locales = Locales::build();
        $this->currentLocale = app()->getLocale();
    }

    public function shouldRender(): bool
    {
        return count($this->locales) > 1;
    }

    public function render()
    {
        return view('waterhole::components.index-footer-language');
    }
}

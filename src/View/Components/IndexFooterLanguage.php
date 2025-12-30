<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Extend\Assets\Locales;

class IndexFooterLanguage extends Component
{
    public array $locales;
    public string $currentLocale;

    public function __construct()
    {
        $this->locales = resolve(Locales::class)->items();
        $this->currentLocale = app()->getLocale();
    }

    public function shouldRender(): bool
    {
        return count($this->locales) > 1;
    }

    public function render()
    {
        return $this->view('waterhole::components.index-footer-language');
    }
}

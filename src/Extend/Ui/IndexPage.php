<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\IndexFooterLanguage;
use Waterhole\View\Components\IndexNav;

/**
 * Sidebar and footer components for index and channel pages.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class IndexPage
{
    public ComponentList $sidebar;
    public ComponentList $footer;

    public function __construct()
    {
        $this->sidebar = (new ComponentList())->add('nav', IndexNav::class);

        $this->footer = (new ComponentList())->add('language', IndexFooterLanguage::class);
    }
}

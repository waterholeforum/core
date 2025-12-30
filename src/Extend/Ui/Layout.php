<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\EmailVerification;
use Waterhole\View\Components\Header;
use Waterhole\View\Components\HeaderBreadcrumb;
use Waterhole\View\Components\HeaderGuest;
use Waterhole\View\Components\HeaderNotifications;
use Waterhole\View\Components\HeaderSearch;
use Waterhole\View\Components\HeaderTitle;
use Waterhole\View\Components\HeaderUser;
use Waterhole\View\Components\Spacer;
use Waterhole\View\Components\ThemeSelector;

/**
 * Top-level layout slots (header, before, after) for forum pages.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class Layout
{
    public ComponentList $header;
    public ComponentList $before;
    public ComponentList $after;

    public function __construct()
    {
        $this->header = (new ComponentList())
            ->add('title', HeaderTitle::class)
            ->add('breadcrumb', HeaderBreadcrumb::class)
            ->add('spacer', Spacer::class)
            ->add('search', HeaderSearch::class)
            ->add('theme', ThemeSelector::class)
            ->add('notifications', HeaderNotifications::class)
            ->add('guest', HeaderGuest::class)
            ->add('user', HeaderUser::class);

        $this->before = (new ComponentList())
            ->add('header', Header::class)
            ->add('email-verification', EmailVerification::class);

        $this->after = new ComponentList();
    }
}

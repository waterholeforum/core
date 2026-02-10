<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\EmailVerification;
use Waterhole\View\Components\Header;
use Waterhole\View\Components\HeaderBreadcrumb;
use Waterhole\View\Components\HeaderGuest;
use Waterhole\View\Components\HeaderModeration;
use Waterhole\View\Components\HeaderNotifications;
use Waterhole\View\Components\HeaderSaved;
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
            ->add(HeaderTitle::class, 'title')
            ->add(HeaderBreadcrumb::class, 'breadcrumb')
            ->add(Spacer::class, 'spacer')
            ->add(HeaderSearch::class, 'search')
            ->add(ThemeSelector::class, 'theme')
            ->add(HeaderModeration::class, 'moderation')
            ->add(HeaderSaved::class, 'saved')
            ->add(HeaderNotifications::class, 'notifications')
            ->add(HeaderGuest::class, 'guest')
            ->add(HeaderUser::class, 'user');

        $this->before = (new ComponentList())
            ->add(Header::class, 'header')
            ->add(EmailVerification::class, 'email-verification');

        $this->after = new ComponentList();
    }
}

<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\EmailVerification;
use Waterhole\View\Components\Header;

/**
 * A list of components to render in the layout before the main content.
 */
abstract class LayoutBefore
{
    use OrderedList;
}

LayoutBefore::add('header', Header::class);
LayoutBefore::add('email-verification', EmailVerification::class);

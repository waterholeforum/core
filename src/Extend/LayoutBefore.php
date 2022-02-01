<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\EmailVerification;
use Waterhole\Views\Components\Header;

/**
 * A list of components to render in the layout before the main content.
 */
abstract class LayoutBefore
{
    use OrderedList;
}

LayoutBefore::add('header', Header::class);
LayoutBefore::add('email-verification', EmailVerification::class);

<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\EmailVerification;
use Waterhole\View\Components\Header;

/**
 * A list of components to render in the layout before the main content.
 */
abstract class LayoutBefore
{
    use OrderedList, OfComponents;
}

LayoutBefore::add(Header::class, 0, 'header');
LayoutBefore::add(EmailVerification::class, 0, 'email-verification');

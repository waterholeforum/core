<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;

/**
 * A list of components to render at the top of the admin dashboard.
 */
abstract class AdminAlerts
{
    use OrderedList;
}

if (config('app.debug')) {
    AdminAlerts::add('debug');
}

if (!config('mail.from.address')) {
    AdminAlerts::add('mail');
}

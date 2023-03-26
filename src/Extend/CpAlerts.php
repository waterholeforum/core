<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;

/**
 * A list of components to render at the top of the CP Dashboard.
 */
abstract class CpAlerts
{
    use OrderedList;
}

if (config('app.debug')) {
    CpAlerts::add(null, 0, 'debug');
}

if (!config('mail.from.address')) {
    CpAlerts::add(null, 0, 'mail');
}

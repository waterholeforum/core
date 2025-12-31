<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;

/**
 * Alert components rendered on the control panel dashboard.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class CpAlerts extends ComponentList
{
    public function __construct()
    {
        if (config('app.debug')) {
            $this->add(null, 'debug');
        }

        if (!config('mail.from.address')) {
            $this->add(null, 'mail');
        }
    }
}

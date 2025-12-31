<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;

/**
 * Components rendered on the user preferences pages.
 *
 * Use this extender to add, remove, or reorder components on preferences pages.
 */
class Preferences
{
    public ComponentList $account;

    public function __construct()
    {
        $this->account = (new ComponentList())
            ->add(null, 'name')
            ->add(null, 'email')
            ->add(null, 'password')
            ->add(null, 'delete');
    }
}

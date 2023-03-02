<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;

abstract class PreferencesAccount
{
    use OrderedList, OfComponents;
}

PreferencesAccount::add(null, -100, 'name');
PreferencesAccount::add(null, -90, 'email');
PreferencesAccount::add(null, -80, 'password');
PreferencesAccount::add(null, 100, 'delete');

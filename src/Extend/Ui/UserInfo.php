<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\UserGroups;
use Waterhole\View\Components\UserJoined;
use Waterhole\View\Components\UserLastSeen;
use Waterhole\View\Components\UserLocation;
use Waterhole\View\Components\UserWebsite;

/**
 * Components rendered in the user profile info sidebar.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class UserInfo extends ComponentList
{
    public function __construct()
    {
        $this->add('groups', UserGroups::class);
        $this->add('location', UserLocation::class);
        $this->add('website', UserWebsite::class);
        $this->add('joined', UserJoined::class);
        $this->add('lastSeen', UserLastSeen::class);
    }
}

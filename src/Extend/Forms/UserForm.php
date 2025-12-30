<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\UserAvatar;
use Waterhole\Forms\Fields\UserBio;
use Waterhole\Forms\Fields\UserEmail;
use Waterhole\Forms\Fields\UserGroups;
use Waterhole\Forms\Fields\UserHeadline;
use Waterhole\Forms\Fields\UserLocation;
use Waterhole\Forms\Fields\UserName;
use Waterhole\Forms\Fields\UserPassword;
use Waterhole\Forms\Fields\UserShowOnline;
use Waterhole\Forms\Fields\UserWebsite;
use Waterhole\Forms\FormSection;

/**
 * List of fields for the control panel user create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class UserForm extends ComponentList
{
    public ComponentList $account;
    public ComponentList $profile;

    public function __construct()
    {
        $this->add(
            'account',
            fn($model) => new FormSection(
                __('waterhole::cp.user-account-title'),
                $this->account->components(compact('model')),
            ),
        );

        $this->account = (new ComponentList())
            ->add('name', UserName::class)
            ->add('email', UserEmail::class)
            ->add('password', UserPassword::class)
            ->add('groups', UserGroups::class);

        $this->add(
            'profile',
            fn($model) => new FormSection(
                __('waterhole::cp.user-profile-title'),
                $this->profile->components(compact('model')),
                open: false,
            ),
        );

        $this->profile = (new ComponentList())
            ->add('avatar', UserAvatar::class)
            ->add('headline', UserHeadline::class)
            ->add('bio', UserBio::class)
            ->add('location', UserLocation::class)
            ->add('website', UserWebsite::class)
            ->add('showOnline', UserShowOnline::class);
    }
}

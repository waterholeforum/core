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
            fn($model) => new FormSection(
                __('waterhole::cp.user-account-title'),
                $this->account->components(compact('model')),
            ),
            'account',
        );

        $this->account = (new ComponentList())
            ->add(UserName::class, 'name')
            ->add(UserEmail::class, 'email')
            ->add(UserPassword::class, 'password')
            ->add(UserGroups::class, 'groups');

        $this->add(
            fn($model) => new FormSection(
                __('waterhole::cp.user-profile-title'),
                $this->profile->components(compact('model')),
                open: false,
            ),
            'profile',
        );

        $this->profile = (new ComponentList())
            ->add(UserAvatar::class, 'avatar')
            ->add(UserHeadline::class, 'headline')
            ->add(UserBio::class, 'bio')
            ->add(UserLocation::class, 'location')
            ->add(UserWebsite::class, 'website')
            ->add(UserShowOnline::class, 'showOnline');
    }
}

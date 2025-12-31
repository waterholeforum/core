<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Channel users JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for channel users.
 */
class ChannelUsersResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->fields
            ->add(ToOne::make('channel'), 'channel')

            ->add(ToOne::make('user'), 'user')

            ->add(
                Attribute::make('notifications')
                    ->type(Type\Str::make()->enum(['normal', 'follow', 'ignore']))
                    ->nullable(),
                'notifications',
            )

            ->add(
                Attribute::make('followedAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
                'followedAt',
            );
    }
}

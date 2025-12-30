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
            ->add('channel', ToOne::make('channel'))

            ->add('user', ToOne::make('user'))

            ->add(
                'notifications',
                Attribute::make('notifications')
                    ->type(Type\Str::make()->enum(['normal', 'follow', 'ignore']))
                    ->nullable(),
            )

            ->add(
                'followedAt',
                Attribute::make('followedAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
            );
    }
}

<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Post users JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for post users.
 */
class PostUsersResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->fields
            ->add(ToOne::make('post'), 'post')

            ->add(ToOne::make('user'), 'user')

            ->add(
                Attribute::make('notifications')
                    ->type(Type\Str::make()->enum(['normal', 'follow', 'ignore']))
                    ->nullable(),
                'notifications',
            )

            ->add(
                Attribute::make('lastReadAt')->type(Type\DateTime::make())->nullable(),
                'lastReadAt',
            )

            ->add(
                Attribute::make('followedAt')->type(Type\DateTime::make())->nullable(),
                'followedAt',
            )

            ->add(
                Attribute::make('mentionedAt')->type(Type\DateTime::make())->nullable(),
                'mentionedAt',
            );
    }
}

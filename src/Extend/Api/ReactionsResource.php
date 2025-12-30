<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Reactions JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for reactions.
 */
class ReactionsResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->fields
            ->add(
                'createdAt',
                Attribute::make('createdAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
            )

            ->add('reactionType', ToOne::make('reactionType'))

            ->add('user', ToOne::make('user'))

            ->add('content', ToOne::make('content')->type(['posts', 'comments']));
    }
}

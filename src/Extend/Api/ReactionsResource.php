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
                Attribute::make('createdAt')->type(Type\DateTime::make())->nullable(),
                'createdAt',
            )

            ->add(ToOne::make('reactionType'), 'reactionType')

            ->add(ToOne::make('user'), 'user')

            ->add(ToOne::make('content')->type(['posts', 'comments']), 'content');
    }
}

<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToMany;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Reaction sets JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for reaction sets.
 */
class ReactionSetsResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->fields
            ->add('name', Attribute::make('name')->type(Type\Str::make()))

            ->add('allowMultiple', Attribute::make('allowMultiple')->type(Type\Boolean::make()))

            ->add('reactionTypes', ToMany::make('reactionTypes')->includable());
    }
}

<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;
use Waterhole\Models\ReactionType;
use function Waterhole\icon;

/**
 * Reaction types JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for reaction types.
 */
class ReactionTypesResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->fields
            ->add('reactionSet', ToOne::make('reactionSet'))

            ->add('name', Attribute::make('name')->type(Type\Str::make()))

            ->add(
                'iconHtml',
                Attribute::make('iconHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable()
                    ->get(fn(ReactionType $reactionType) => icon($reactionType->icon)),
            )

            ->add('score', Attribute::make('score')->type(Type\Integer::make()));
    }
}

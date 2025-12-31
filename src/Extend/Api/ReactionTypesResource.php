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
            ->add(ToOne::make('reactionSet'), 'reactionSet')

            ->add(Attribute::make('name')->type(Type\Str::make()), 'name')

            ->add(
                Attribute::make('iconHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable()
                    ->get(fn(ReactionType $reactionType) => icon($reactionType->icon)),
                'iconHtml',
            )

            ->add(Attribute::make('score')->type(Type\Integer::make()), 'score');
    }
}

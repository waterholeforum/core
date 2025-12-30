<?php

namespace Waterhole\Api\Resources;

use Illuminate\Support\Facades\Auth;
use Tobyz\JsonApiServer\Context;
use Tobyz\JsonApiServer\Resource\AbstractResource;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Models\ReactionType;

class ReactionCountsResource extends AbstractResource
{
    public function type(): string
    {
        return 'reactionCounts';
    }

    public function getId(object $model, Context $context): string
    {
        return implode('-', [$model->content_type, $model->content_id, $model->id]);
    }

    public function fields(): array
    {
        return [
            Attribute::make('count')->type(Type\Integer::make()),

            Attribute::make('userReacted')
                ->type(Type\Boolean::make())
                ->visible(fn() => Auth::check()),

            ToOne::make('reactionType')
                ->get(fn(ReactionType $reactionType) => $reactionType)
                ->includable(),
        ];
    }
}

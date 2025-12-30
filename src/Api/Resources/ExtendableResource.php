<?php

namespace Waterhole\Api\Resources;

use Illuminate\Database\Eloquent\Builder;
use Tobyz\JsonApiServer\Context;
use Tobyz\JsonApiServer\Laravel\EloquentResource;
use Waterhole\Extend\Support\Resource;

abstract class ExtendableResource extends EloquentResource
{
    protected Resource $extender;

    public function scope(Builder $query, Context $context): void
    {
        foreach ($this->extender->scope->values() as $callback) {
            $callback($query, $context);
        }
    }

    public function endpoints(): array
    {
        return $this->extender->endpoints->values();
    }

    public function fields(): array
    {
        return $this->extender->fields->values();
    }

    public function sorts(): array
    {
        return $this->extender->sorts->values();
    }

    public function filters(): array
    {
        return $this->extender->filters->values();
    }
}

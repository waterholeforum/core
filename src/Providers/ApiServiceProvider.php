<?php

namespace Waterhole\Providers;

use Illuminate\Support\ServiceProvider;
use Tobyz\JsonApiServer\Extension\Atomic\Atomic;
use Waterhole\Api\Collections;
use Waterhole\Api\Resources;
use Waterhole\Extend\Api\JsonApi as JsonApiExtender;

class ApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(JsonApiExtender::class, function ($container) {
            $api = new JsonApiExtender(route('waterhole.api.main'));

            $api->extension(new Atomic());

            $api->resource($container->make(Resources\ChannelsResource::class));
            $api->resource($container->make(Resources\ChannelUsersResource::class));
            $api->resource($container->make(Resources\BookmarksResource::class));
            $api->resource($container->make(Resources\CommentsResource::class));
            $api->resource($container->make(Resources\GroupsResource::class));
            $api->resource($container->make(Resources\MentionsResource::class));
            $api->resource($container->make(Resources\PagesResource::class));
            $api->resource($container->make(Resources\PostsResource::class));
            $api->resource($container->make(Resources\PostUsersResource::class));
            $api->resource($container->make(Resources\ReactionSetsResource::class));
            $api->resource($container->make(Resources\ReactionsResource::class));
            $api->resource($container->make(Resources\ReactionCountsResource::class));
            $api->resource($container->make(Resources\ReactionTypesResource::class));
            $api->resource($container->make(Resources\StructureResource::class));
            $api->resource($container->make(Resources\StructureHeadingsResource::class));
            $api->resource($container->make(Resources\StructureLinksResource::class));
            $api->resource($container->make(Resources\TagsResource::class));
            $api->resource($container->make(Resources\TaxonomiesResource::class));
            $api->resource($container->make(Resources\UsersResource::class));

            $api->collection($container->make(Collections\StructureContentCollection::class));

            return $api;
        });

        $this->app->alias(JsonApiExtender::class, 'waterhole.json-api');
    }
}

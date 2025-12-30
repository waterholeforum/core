<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToMany;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;
use Waterhole\Models\Channel;
use function Waterhole\icon;

/**
 * Channels JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for channels.
 */
class ChannelsResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->endpoints->add('show', Endpoint\Show::make());

        $this->fields
            ->add('name', Attribute::make('name')->type(Type\Str::make()))

            ->add(
                'iconHtml',
                Attribute::make('iconHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable()
                    ->get(fn(Channel $channel) => icon($channel->icon)),
            )

            ->add(
                'descriptionHtml',
                Attribute::make('descriptionHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable(),
            )

            ->add(
                'instructionsHtml',
                Attribute::make('instructionsHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable(),
            )

            ->add('url', Attribute::make('url')->type(Type\Str::make()->format('uri')))

            ->add(
                'postsReactionSet',
                ToOne::make('postsReactionSet')
                    ->type('reactionSets')
                    ->nullable()
                    ->includable(),
            )

            ->add(
                'commentsReactionSet',
                ToOne::make('commentsReactionSet')
                    ->type('reactionSets')
                    ->nullable()
                    ->includable(),
            )

            ->add('taxonomies', ToMany::make('taxonomies')->includable())

            ->add('posts', ToMany::make('posts'))

            ->add(
                'userState',
                ToOne::make('userState')
                    ->type('channelUsers')
                    ->includable(),
            );
    }
}

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

        $this->endpoints->add(Endpoint\Show::make(), 'show');

        $this->fields
            ->add(Attribute::make('name')->type(Type\Str::make()), 'name')

            ->add(
                Attribute::make('iconHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable()
                    ->get(fn(Channel $channel) => icon($channel->icon)),
                'iconHtml',
            )

            ->add(
                Attribute::make('descriptionHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable(),
                'descriptionHtml',
            )

            ->add(
                Attribute::make('instructionsHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable(),
                'instructionsHtml',
            )

            ->add(Attribute::make('url')->type(Type\Str::make()->format('uri')), 'url')

            ->add(
                ToOne::make('postsReactionSet')
                    ->type('reactionSets')
                    ->nullable()
                    ->includable(),
                'postsReactionSet',
            )

            ->add(
                ToOne::make('commentsReactionSet')
                    ->type('reactionSets')
                    ->nullable()
                    ->includable(),
                'commentsReactionSet',
            )

            ->add(ToMany::make('taxonomies')->includable(), 'taxonomies')

            ->add(ToMany::make('posts'), 'posts')

            ->add(
                ToOne::make('userState')
                    ->type('channelUsers')
                    ->includable(),
                'userState',
            );
    }
}

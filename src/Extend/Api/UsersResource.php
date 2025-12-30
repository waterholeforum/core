<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Laravel\Filter\Where;
use Tobyz\JsonApiServer\Laravel\Sort\SortColumn;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToMany;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Users JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for users.
 */
class UsersResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->endpoints
            ->add('index', Endpoint\Index::make())

            ->add('show', Endpoint\Show::make());

        $this->fields
            ->add('name', Attribute::make('name')->type(Type\Str::make()))

            // TODO: visibility
            ->add('email', Attribute::make('email')->type(Type\Str::make()->format('email')))

            // TODO: visibility
            ->add('locale', Attribute::make('locale')->type(Type\Str::make()))

            ->add(
                'headline',
                Attribute::make('headline')
                    ->type(Type\Str::make())
                    ->nullable(),
            )

            ->add(
                'bio',
                Attribute::make('bio')
                    ->type(Type\Str::make())
                    ->nullable(),
            )

            ->add(
                'location',
                Attribute::make('location')
                    ->type(Type\Str::make())
                    ->nullable(),
            )

            ->add(
                'website',
                Attribute::make('website')
                    ->type(Type\Str::make()->format('uri'))
                    ->nullable(),
            )

            ->add(
                'avatarUrl',
                Attribute::make('avatarUrl')
                    ->type(Type\Str::make()->format('uri'))
                    ->nullable(),
            )

            ->add(
                'createdAt',
                Attribute::make('createdAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
            )

            // TODO: visibility
            ->add(
                'lastSeenAt',
                Attribute::make('lastSeenAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
            )

            // TODO: visibility
            ->add(
                'suspendedUntil',
                Attribute::make('suspendedUntil')
                    ->type(Type\DateTime::make())
                    ->nullable(),
            )

            ->add('url', Attribute::make('url')->type(Type\Str::make()->format('uri')))

            ->add('posts', ToMany::make('posts'))

            ->add('comments', ToMany::make('comments'))

            ->add('groups', ToMany::make('groups')->includable());

        $this->sorts
            ->add('name', SortColumn::make('name'))

            ->add('createdAt', SortColumn::make('createdAt'))

            // TODO: visibility
            ->add('lastSeenAt', SortColumn::make('lastSeenAt'));

        $this->filters
            ->add('id', Where::make('id'))

            ->add('email', Where::make('email'));

        // name LIKE
        // isSuspended
        // groups)
    }
}

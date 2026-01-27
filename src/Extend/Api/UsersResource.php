<?php

namespace Waterhole\Extend\Api;

use Illuminate\Support\Facades\Auth;
use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Laravel\Filter\Where;
use Tobyz\JsonApiServer\Laravel\Sort\SortColumn;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToMany;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;
use Waterhole\Models\User;

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
            ->add(Endpoint\Index::make()->visible(fn() => Auth::user()?->isAdmin()), 'index')

            ->add(Endpoint\Show::make(), 'show');

        $canViewPrivate = fn(User $user) => ($actor = Auth::user()) &&
            ($actor->isAdmin() || $actor->is($user));

        $this->fields
            ->add(Attribute::make('name')->type(Type\Str::make()), 'name')

            ->add(
                Attribute::make('email')
                    ->type(Type\Str::make()->format('email'))
                    ->visible($canViewPrivate),
                'email',
            )

            ->add(
                Attribute::make('locale')->type(Type\Str::make())->visible($canViewPrivate),
                'locale',
            )

            ->add(Attribute::make('headline')->type(Type\Str::make())->nullable(), 'headline')

            ->add(Attribute::make('bio')->type(Type\Str::make())->nullable(), 'bio')

            ->add(Attribute::make('location')->type(Type\Str::make())->nullable(), 'location')

            ->add(
                Attribute::make('website')
                    ->type(Type\Str::make()->format('uri'))
                    ->nullable(),
                'website',
            )

            ->add(
                Attribute::make('avatarUrl')
                    ->type(Type\Str::make()->format('uri'))
                    ->nullable(),
                'avatarUrl',
            )

            ->add(
                Attribute::make('createdAt')->type(Type\DateTime::make())->nullable(),
                'createdAt',
            )

            ->add(
                Attribute::make('lastSeenAt')
                    ->type(Type\DateTime::make())
                    ->nullable()
                    ->visible(fn(User $user) => $user->show_online || $canViewPrivate($user)),
                'lastSeenAt',
            )

            ->add(
                Attribute::make('suspendedUntil')
                    ->type(Type\DateTime::make())
                    ->nullable()
                    ->visible($canViewPrivate),
                'suspendedUntil',
            )

            ->add(Attribute::make('url')->type(Type\Str::make()->format('uri')), 'url')

            ->add(ToMany::make('posts'), 'posts')

            ->add(ToMany::make('comments'), 'comments')

            ->add(ToMany::make('groups')->includable(), 'groups');

        $this->sorts
            ->add(SortColumn::make('name'), 'name')

            ->add(SortColumn::make('createdAt'), 'createdAt')

            ->add(SortColumn::make('lastSeenAt'), 'lastSeenAt');

        $this->filters
            ->add(Where::make('id'), 'id')

            ->add(Where::make('email'), 'email');

        // name LIKE
        // isSuspended
        // groups)
    }
}

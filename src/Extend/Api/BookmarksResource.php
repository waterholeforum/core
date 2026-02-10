<?php

namespace Waterhole\Extend\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Bookmarks JSON:API resource.
 *
 * Defines fields, endpoints, and scope for bookmarks.
 */
class BookmarksResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->scope->add(function (Builder $query) {
            $query->visible(Auth::user());
        }, 'visible');

        $this->endpoints
            ->add(Endpoint\Index::make()->paginate()->defaultSort('-createdAt'), 'index')
            ->add(Endpoint\Show::make(), 'show');

        $this->fields
            ->add(
                Attribute::make('createdAt')->type(Type\DateTime::make())->nullable(),
                'createdAt',
            )
            ->add(
                Attribute::make('updatedAt')->type(Type\DateTime::make())->nullable(),
                'updatedAt',
            )
            ->add(ToOne::make('user')->type('users'), 'user')
            ->add(ToOne::make('content')->type(['posts', 'comments']), 'content');
    }
}

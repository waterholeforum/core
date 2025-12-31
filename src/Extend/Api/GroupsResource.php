<?php

namespace Waterhole\Extend\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToMany;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;
use Waterhole\Models\Group;
use function Waterhole\icon;

/**
 * Groups JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for groups.
 */
class GroupsResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->scope->add(function (Builder $query) {
            if (!Auth::user()?->can('waterhole.user.edit')) {
                $query->where('is_public', true);
            }
        }, 'default');

        $this->endpoints
            ->add(Endpoint\Index::make()->paginate(), 'index')

            ->add(Endpoint\Show::make(), 'show');

        $this->fields
            ->add(Attribute::make('name')->type(Type\Str::make()), 'name')

            ->add(Attribute::make('isPublic')->type(Type\Boolean::make()), 'isPublic')

            ->add(Attribute::make('color')->type(Type\Str::make()), 'color')

            ->add(
                Attribute::make('iconHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable()
                    ->get(fn(Group $group) => icon($group->icon)),
                'iconHtml',
            )

            ->add(ToMany::make('users'), 'users');
    }
}

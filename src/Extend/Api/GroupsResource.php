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

        $this->scope->add('default', function (Builder $query) {
            if (!Auth::user()?->can('waterhole.user.edit')) {
                $query->where('is_public', true);
            }
        });

        $this->endpoints
            ->add('index', Endpoint\Index::make()->paginate())

            ->add('show', Endpoint\Show::make());

        $this->fields
            ->add('name', Attribute::make('name')->type(Type\Str::make()))

            ->add('isPublic', Attribute::make('isPublic')->type(Type\Boolean::make()))

            ->add('color', Attribute::make('color')->type(Type\Str::make()))

            ->add(
                'iconHtml',
                Attribute::make('iconHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable()
                    ->get(fn(Group $group) => icon($group->icon)),
            )

            ->add('users', ToMany::make('users'));
    }
}

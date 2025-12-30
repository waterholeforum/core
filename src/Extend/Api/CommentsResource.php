<?php

namespace Waterhole\Extend\Api;

use Illuminate\Database\Eloquent\Builder;
use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Laravel\Filter\WhereBelongsTo;
use Tobyz\JsonApiServer\Laravel\Filter\WhereNotNull;
use Tobyz\JsonApiServer\Laravel\Sort\SortColumn;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToMany;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Comments JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for comments.
 */
class CommentsResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->scope->add('default', function (Builder $query) {
            // Required to generate URLs
            $query->with('post');
        });

        $this->endpoints
            ->add(
                'index',
                Endpoint\Index::make()
                    ->paginate()
                    ->defaultSort('-createdAt'),
            )

            ->add('show', Endpoint\Show::make());

        $this->fields
            ->add('post', ToOne::make('post')->includable())

            ->add(
                'parent',
                ToOne::make('parent')
                    ->type('comments')
                    ->nullable()
                    ->includable(),
            )

            ->add(
                'user',
                ToOne::make('user')
                    ->nullable()
                    ->includable(),
            )

            ->add(
                'body',
                Attribute::make('body')
                    ->type(Type\Str::make())
                    ->sparse(),
            )

            ->add(
                'bodyText',
                Attribute::make('bodyText')
                    ->type(Type\Str::make())
                    ->sparse(),
            )

            ->add('bodyHtml', Attribute::make('bodyHtml')->type(Type\Str::make()->format('html')))

            ->add('createdAt', Attribute::make('createdAt')->type(Type\DateTime::make()))

            ->add(
                'editedAt',
                Attribute::make('editedAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
            )

            ->add('replyCount', Attribute::make('replyCount')->type(Type\Integer::make()))

            ->add(
                'hiddenAt',
                Attribute::make('hiddenAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
            )

            ->add(
                'hiddenBy',
                ToOne::make('hiddenBy')
                    ->type('users')
                    ->nullable(),
            )

            ->add(
                'hiddenReason',
                Attribute::make('hiddenReason')
                    ->type(Type\Str::make())
                    ->nullable(),
            )

            ->add('replies', ToMany::make('replies')->type('comments'))

            ->add('url', Attribute::make('url')->type(Type\Str::make()->format('uri')))

            ->add('postUrl', Attribute::make('postUrl')->type(Type\Str::make()->format('uri')))

            ->add('reactionCounts', ToMany::make('reactionCounts')->includable())

            ->add('reactions', ToMany::make('reactions')->includable());

        $this->sorts
            ->add('createdAt', SortColumn::make('createdAt'))
            ->add('score', SortColumn::make('score'));

        $this->filters
            ->add('post', WhereBelongsTo::make('post'))
            ->add('parent', WhereBelongsTo::make('parent'))
            ->add('user', WhereBelongsTo::make('user'))
            ->add('isHidden', WhereNotNull::make('isHidden')->column('hidden_at'));
    }
}

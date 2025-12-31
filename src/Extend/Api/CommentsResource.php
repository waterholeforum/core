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

        $this->scope->add(function (Builder $query) {
            // Required to generate URLs
            $query->with('post');
        }, 'default');

        $this->endpoints
            ->add(
                Endpoint\Index::make()
                    ->paginate()
                    ->defaultSort('-createdAt'),
                'index',
            )

            ->add(Endpoint\Show::make(), 'show');

        $this->fields
            ->add(ToOne::make('post')->includable(), 'post')

            ->add(
                ToOne::make('parent')
                    ->type('comments')
                    ->nullable()
                    ->includable(),
                'parent',
            )

            ->add(
                ToOne::make('user')
                    ->nullable()
                    ->includable(),
                'user',
            )

            ->add(
                Attribute::make('body')
                    ->type(Type\Str::make())
                    ->sparse(),
                'body',
            )

            ->add(
                Attribute::make('bodyText')
                    ->type(Type\Str::make())
                    ->sparse(),
                'bodyText',
            )

            ->add(
                Attribute::make('bodyHtml')->type(Type\Str::make()->format('html')),
                'bodyHtml',
            )

            ->add(Attribute::make('createdAt')->type(Type\DateTime::make()), 'createdAt')

            ->add(
                Attribute::make('editedAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
                'editedAt',
            )

            ->add(Attribute::make('replyCount')->type(Type\Integer::make()), 'replyCount')

            ->add(
                Attribute::make('hiddenAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
                'hiddenAt',
            )

            ->add(
                ToOne::make('hiddenBy')
                    ->type('users')
                    ->nullable(),
                'hiddenBy',
            )

            ->add(
                Attribute::make('hiddenReason')
                    ->type(Type\Str::make())
                    ->nullable(),
                'hiddenReason',
            )

            ->add(ToMany::make('replies')->type('comments'), 'replies')

            ->add(Attribute::make('url')->type(Type\Str::make()->format('uri')), 'url')

            ->add(
                Attribute::make('postUrl')->type(Type\Str::make()->format('uri')),
                'postUrl',
            )

            ->add(ToMany::make('reactionCounts')->includable(), 'reactionCounts')

            ->add(ToMany::make('reactions')->includable(), 'reactions');

        $this->sorts
            ->add(SortColumn::make('createdAt'), 'createdAt')
            ->add(SortColumn::make('score'), 'score');

        $this->filters
            ->add(WhereBelongsTo::make('post'), 'post')
            ->add(WhereBelongsTo::make('parent'), 'parent')
            ->add(WhereBelongsTo::make('user'), 'user')
            ->add(WhereNotNull::make('isHidden')->column('hidden_at'), 'isHidden');
    }
}

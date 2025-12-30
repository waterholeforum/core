<?php

namespace Waterhole\Extend\Api;

use Illuminate\Support\Facades\Auth;
use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Laravel\Filter\Scope;
use Tobyz\JsonApiServer\Laravel\Filter\Where;
use Tobyz\JsonApiServer\Laravel\Filter\WhereBelongsTo;
use Tobyz\JsonApiServer\Laravel\Filter\WhereExists;
use Tobyz\JsonApiServer\Laravel\Filter\WhereHas;
use Tobyz\JsonApiServer\Laravel\Filter\WhereNull;
use Tobyz\JsonApiServer\Laravel\Sort\SortColumn;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToMany;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Posts JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for posts.
 */
class PostsResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->scope->add('default', function ($query) {
            $query->with('mentions', 'attachments');
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
            ->add('title', Attribute::make('title')->type(Type\Str::make()))

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

            ->add(
                'deletedAt',
                Attribute::make('deletedAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
            )

            ->add(
                'lastActivityAt',
                Attribute::make('lastActivityAt')
                    ->type(Type\DateTime::make())
                    ->nullable(),
            )

            ->add('commentCount', Attribute::make('commentCount')->type(Type\Integer::make()))

            ->add('viewCount', Attribute::make('viewCount')->type(Type\Integer::make()))

            ->add('isLocked', Attribute::make('isLocked')->type(Type\Boolean::make()))

            ->add('isPinned', Attribute::make('isPinned')->type(Type\Boolean::make()))

            ->add('url', Attribute::make('url')->type(Type\Str::make()->format('uri')))

            ->add('channel', ToOne::make('channel')->includable())

            ->add(
                'user',
                ToOne::make('user')
                    ->includable()
                    ->nullable(),
            )

            ->add('comments', ToMany::make('comments'))

            ->add(
                'lastComment',
                ToOne::make('lastComment')
                    ->type('comments')
                    ->nullable()
                    ->withoutLinkage()
                    ->includable(),
            )

            ->add(
                'answer',
                ToOne::make('answer')
                    ->type('comments')
                    ->nullable()
                    ->includable(),
            )

            ->add('tags', ToMany::make('tags')->includable())

            ->add('reactionCounts', ToMany::make('reactionCounts')->includable())

            ->add('reactions', ToMany::make('reactions')->includable())

            ->add(
                'userState',
                ToOne::make('userState')
                    ->type('postUsers')
                    ->nullable()
                    ->visible(fn() => Auth::check())
                    ->includable(),
            );

        $this->sorts
            ->add('title', SortColumn::make('title'))
            ->add('createdAt', SortColumn::make('createdAt'))
            ->add('lastActivityAt', SortColumn::make('lastActivityAt'))
            ->add('commentCount', SortColumn::make('commentCount'))
            ->add('viewCount', SortColumn::make('viewCount'))
            ->add('score', SortColumn::make('score'))
            ->add('hotness', SortColumn::make('hotness'));

        $this->filters
            ->add('unread', Scope::make('unread'))
            ->add('following', Scope::make('following'))
            ->add('ignoring', Scope::make('ignoring'))
            ->add('isLocked', Where::make('isLocked')->asBoolean())
            ->add('isPinned', Where::make('isPinned')->asBoolean())
            ->add('isTrashed', WhereNull::make('isTrashed')->column('deleted_at'))
            ->add('channel', WhereBelongsTo::make('channel'))
            ->add('user', WhereBelongsTo::make('user'))
            ->add('tags', WhereHas::make('tags'))
            ->add('answer', WhereExists::make('answer'));
    }
}

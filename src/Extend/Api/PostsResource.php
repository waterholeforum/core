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
use function Tobyz\JsonApiServer\Laravel\can;

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

        $this->scope->add(function ($query) {
            $query->with('mentions.mentionable', 'attachments');
        }, 'default');

        $this->endpoints
            ->add(Endpoint\Index::make()->paginate()->defaultSort('-createdAt'), 'index')

            ->add(Endpoint\Show::make(), 'show');

        $this->fields
            ->add(Attribute::make('title')->type(Type\Str::make()), 'title')

            ->add(Attribute::make('body')->type(Type\Str::make())->sparse(), 'body')

            ->add(Attribute::make('bodyText')->type(Type\Str::make())->sparse(), 'bodyText')

            ->add(Attribute::make('bodyHtml')->type(Type\Str::make()->format('html')), 'bodyHtml')

            ->add(Attribute::make('createdAt')->type(Type\DateTime::make()), 'createdAt')

            ->add(Attribute::make('editedAt')->type(Type\DateTime::make())->nullable(), 'editedAt')

            ->add(
                Attribute::make('deletedAt')->type(Type\DateTime::make())->nullable(),
                'deletedAt',
            )

            ->add(
                ToOne::make('deletedBy')
                    ->type('users')
                    ->nullable()
                    ->visible(can('waterhole.post.moderate')),
                'deletedBy',
            )

            ->add(
                Attribute::make('deletedReason')->type(Type\Str::make())->nullable(),
                'deletedReason',
            )

            ->add(
                Attribute::make('lastActivityAt')->type(Type\DateTime::make())->nullable(),
                'lastActivityAt',
            )

            ->add(Attribute::make('commentCount')->type(Type\Integer::make()), 'commentCount')

            ->add(Attribute::make('viewCount')->type(Type\Integer::make()), 'viewCount')

            ->add(Attribute::make('isLocked')->type(Type\Boolean::make()), 'isLocked')

            ->add(Attribute::make('isPinned')->type(Type\Boolean::make()), 'isPinned')

            ->add(Attribute::make('url')->type(Type\Str::make()->format('uri')), 'url')

            ->add(ToOne::make('channel')->includable(), 'channel')

            ->add(ToOne::make('user')->includable()->nullable(), 'user')

            ->add(ToMany::make('comments'), 'comments')

            ->add(
                ToOne::make('lastComment')
                    ->type('comments')
                    ->nullable()
                    ->withoutLinkage()
                    ->includable(),
                'lastComment',
            )

            ->add(ToOne::make('answer')->type('comments')->nullable()->includable(), 'answer')

            ->add(ToMany::make('tags')->includable(), 'tags')

            ->add(ToMany::make('reactionCounts')->includable(), 'reactionCounts')

            ->add(ToMany::make('reactions')->includable(), 'reactions')

            ->add(ToMany::make('mentions')->includable(), 'mentions')

            ->add(
                ToOne::make('userState')
                    ->type('postUsers')
                    ->nullable()
                    ->visible(fn() => Auth::check())
                    ->includable(),
                'userState',
            )

            ->add(
                ToOne::make('bookmark')
                    ->type('bookmarks')
                    ->nullable()
                    ->visible(fn() => Auth::check())
                    ->includable(),
                'bookmark',
            );

        $this->sorts
            ->add(SortColumn::make('title'), 'title')
            ->add(SortColumn::make('createdAt'), 'createdAt')
            ->add(SortColumn::make('lastActivityAt'), 'lastActivityAt')
            ->add(SortColumn::make('commentCount'), 'commentCount')
            ->add(SortColumn::make('viewCount'), 'viewCount')
            ->add(SortColumn::make('score'), 'score')
            ->add(SortColumn::make('hotness'), 'hotness');

        $this->filters
            ->add(Scope::make('unread'), 'unread')
            ->add(Scope::make('following'), 'following')
            ->add(Scope::make('ignoring'), 'ignoring')
            ->add(Where::make('isLocked')->asBoolean(), 'isLocked')
            ->add(Where::make('isPinned')->asBoolean(), 'isPinned')
            ->add(WhereNull::make('isTrashed')->column('deleted_at'), 'isTrashed')
            ->add(WhereBelongsTo::make('channel'), 'channel')
            ->add(WhereBelongsTo::make('user'), 'user')
            ->add(WhereHas::make('tags'), 'tags')
            ->add(WhereExists::make('answer'), 'answer');
    }
}

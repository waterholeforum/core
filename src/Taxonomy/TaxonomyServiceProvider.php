<?php

namespace Waterhole\Taxonomy;

use Arr;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Waterhole\Extend;
use Waterhole\Feed\PostFeed;
use Waterhole\View\Components\NavLink;

class TaxonomyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Extend\AdminRoutes::add(function () {
            Route::resource('taxonomies', TaxonomyController::class)->only(
                'index',
                'create',
                'store',
                'edit',
                'update',
            );

            Route::resource('taxonomies.tags', TagController::class)
                ->only('create', 'store', 'edit', 'update')
                ->scoped();
        });

        Extend\AdminNav::add(
            'taxonomies',
            new NavLink(
                label: __('waterhole::admin.taxonomies-title'),
                icon: 'tabler-tags',
                route: 'waterhole.admin.taxonomies.index',
                active: fn() => request()->routeIs('waterhole.admin.taxonomies*'),
            ),
            -80,
        );

        Extend\Actionables::add('taxonomy', Taxonomy::class);
        Extend\Actionables::add('tag', Tag::class);

        Extend\Actions::add('edit-taxonomy', Actions\EditTaxonomy::class);
        Extend\Actions::add('delete-taxonomy', Actions\DeleteTaxonomy::class);

        Extend\Actions::add('edit-tag', Actions\EditTag::class);
        Extend\Actions::add('delete-tag', Actions\DeleteTag::class);

        Extend\TaxonomyForm::add('name', Fields\TaxonomyName::class);
        Extend\TaxonomyForm::add('options', Fields\TaxonomyOptions::class);

        Extend\TagForm::add('name', Fields\TagName::class);

        Extend\ChannelFormOptions::add('taxonomies', Fields\ChannelTaxonomies::class);

        Extend\PostForm::add('tags', Fields\PostTags::class, position: -15);

        Relation::morphMap(['taxonomy' => Taxonomy::class]);

        Extend\PostInfo::add('tags', Components\PostTagsSummary::class);
        Extend\PostHeader::add('tags', Components\PostTagsSummary::class, position: -95);

        Extend\PostFeedToolbar::add('tags', Components\TagsFilter::class);

        PostFeed::$eagerLoad[] = 'tags';
        PostFeed::$scopes[] = function ($query) {
            $param = request('tags');
            if ($param && ($ids = is_array($param) ? Arr::flatten($param) : [$param])) {
                Tag::findOrFail($ids);
                $query->whereRelation('tags', fn($query) => $query->whereKey($ids));
            }
        };
    }
}

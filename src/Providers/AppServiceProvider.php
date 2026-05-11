<?php

namespace Waterhole\Providers;

use BladeUI\Icons\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Waterhole\Extend;
use Waterhole\Models;
use Waterhole\Notifications\DatabaseChannel;
use Waterhole\Waterhole;

class AppServiceProvider extends ServiceProvider
{
    protected array $configFiles = [
        'api',
        'auth',
        'cp',
        'design',
        'forum',
        'seo',
        'system',
        'uploads',
        'users',
    ];

    protected array $extenders = [
        Extend\Api\BookmarksResource::class,
        Extend\Api\ChannelUsersResource::class,
        Extend\Api\ChannelsResource::class,
        Extend\Api\CommentsResource::class,
        Extend\Api\GroupsResource::class,
        Extend\Api\JsonApi::class,
        Extend\Api\MentionsResource::class,
        Extend\Api\PagesResource::class,
        Extend\Api\PostUsersResource::class,
        Extend\Api\PostsResource::class,
        Extend\Api\ReactionSetsResource::class,
        Extend\Api\ReactionTypesResource::class,
        Extend\Api\ReactionsResource::class,
        Extend\Api\StructureHeadingsResource::class,
        Extend\Api\StructureLinksResource::class,
        Extend\Api\StructureResource::class,
        Extend\Api\TagsResource::class,
        Extend\Api\TaxonomiesResource::class,
        Extend\Api\UsersResource::class,
        Extend\Assets\Locales::class,
        Extend\Assets\Script::class,
        Extend\Assets\Stylesheet::class,
        Extend\Core\Actions::class,
        Extend\Core\Formatter::class,
        Extend\Core\NotificationTypes::class,
        Extend\Core\PostFilters::class,
        Extend\Core\PostLayouts::class,
        Extend\Forms\ChannelForm::class,
        Extend\Forms\GroupForm::class,
        Extend\Forms\PageForm::class,
        Extend\Forms\PostForm::class,
        Extend\Forms\ReactionSetForm::class,
        Extend\Forms\ReactionTypeForm::class,
        Extend\Forms\RegistrationForm::class,
        Extend\Forms\StructureLinkForm::class,
        Extend\Forms\TagForm::class,
        Extend\Forms\TaxonomyForm::class,
        Extend\Forms\UserForm::class,
        Extend\Query\CommentQuery::class,
        Extend\Query\PostFeedQuery::class,
        Extend\Query\PostVisibilityScopes::class,
        Extend\Routing\ApiRoutes::class,
        Extend\Routing\CpRoutes::class,
        Extend\Routing\ForumRoutes::class,
        Extend\Ui\CommentAttributes::class,
        Extend\Ui\CommentComponent::class,
        Extend\Ui\CpAlerts::class,
        Extend\Ui\CpNav::class,
        Extend\Ui\DocumentHead::class,
        Extend\Ui\IndexPage::class,
        Extend\Ui\KeyboardShortcuts::class,
        Extend\Ui\Layout::class,
        Extend\Ui\LoginPage::class,
        Extend\Ui\PostAttributes::class,
        Extend\Ui\PostFeed::class,
        Extend\Ui\PostFooter::class,
        Extend\Ui\PostListItem::class,
        Extend\Ui\PostPage::class,
        Extend\Ui\Preferences::class,
        Extend\Ui\TextEditor::class,
        Extend\Ui\UserInfo::class,
        Extend\Ui\UserMenu::class,
        Extend\Ui\UserNav::class,
    ];

    public function register()
    {
        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom(__DIR__ . "/../../config/$config.php", "waterhole.$config");
        });

        foreach ($this->extenders as $class) {
            $this->app->scoped($class);
        }

        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('waterhole', [
                'path' => __DIR__ . '/../../resources/icons',
                'prefix' => 'waterhole',
            ]);
        });
    }

    public function boot()
    {
        Relation::morphMap([
            'bookmark' => Models\Bookmark::class,
            'channel' => Models\Channel::class,
            'comment' => Models\Comment::class,
            'group' => Models\Group::class,
            'page' => Models\Page::class,
            'post' => Models\Post::class,
            'structureHeading' => Models\StructureHeading::class,
            'structureLink' => Models\StructureLink::class,
            'taxonomy' => Models\Taxonomy::class,
            'user' => Models\User::class,
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        collect($this->configFiles)->each(function ($config) {
            $this->publishes(
                [__DIR__ . "/../../config/$config.php" => config_path("waterhole/$config.php")],
                'waterhole-config',
            );
        });

        // Override the notifications database channel with our own instance.
        // This is necessary to extract special fields from the notification
        // data array, and assign them to real columns in the database so that
        // they can be used to power relationships.
        resolve(ChannelManager::class)->extend('database', function () {
            return new DatabaseChannel();
        });

        $this->addAboutCommandInfo();
    }

    private function addAboutCommandInfo(): void
    {
        AboutCommand::add('Environment', [
            'Waterhole Version' => Waterhole::version(),
        ]);
    }
}

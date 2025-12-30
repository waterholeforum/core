<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Models\Channel;
use Waterhole\Models\ChannelUser;
use Waterhole\Models\Comment;
use Waterhole\Models\Group;
use Waterhole\Models\Page;
use Waterhole\Models\Post;
use Waterhole\Models\PostUser;
use Waterhole\Models\Reaction;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\ReactionType;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;
use Waterhole\Models\Tag;
use Waterhole\Models\Taxonomy;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('API extenders', function () {
    test('add resource', function () {
        $resource = new class extends Tobyz\JsonApiServer\Resource\AbstractResource implements
            Tobyz\JsonApiServer\Resource\Findable
        {
            public function type(): string
            {
                return 'test';
            }

            public function endpoints(): array
            {
                return [Tobyz\JsonApiServer\Endpoint\Show::make()];
            }

            public function find(string $id, Tobyz\JsonApiServer\Context $context): ?object
            {
                return (object) ['id' => $id];
            }
        };

        extend(function (Extend\Api\JsonApi $api) use ($resource) {
            $api->resource($resource);
        });

        jsonApi('GET', '/api/test/1')->assertOk();
    });

    test('add field to api resources with endpoints', function (
        string $extenderClass,
        callable $makeRequest,
    ) {
        app()->extend($extenderClass, function ($resource) {
            $resource->fields->add(
                'extendTest',
                Attribute::make('extendTest')
                    ->type(Type\Str::make())
                    ->get(fn($model) => (string) $model->getKey()),
            );

            return $resource;
        });

        [$response, $path, $value] = $makeRequest($this);

        $response->assertOk();
        $response->assertJsonPath($path, $value);
    })->with('api resources with endpoints');

    test('add field to api related resources', function (
        string $extenderClass,
        callable $makeRequest,
    ) {
        app()->extend($extenderClass, function ($resource) {
            $resource->fields->add(
                'extendTest',
                Attribute::make('extendTest')
                    ->type(Type\Str::make())
                    ->get(fn($model) => (string) $model->getKey()),
            );

            return $resource;
        });

        [$response, $type, $id] = $makeRequest($this);

        $response->assertOk();

        $included = collect($response->json('included'));

        $match = $included->first(
            fn($resource) => $resource['type'] === $type && $resource['id'] === (string) $id,
        );

        expect($match)->not->toBeNull();
        expect($match['attributes']['extendTest'])->toBe((string) $id);
    })->with('api resources with relations');
});

dataset('api resources with endpoints', [
    'channels' => [
        Extend\Api\ChannelsResource::class,
        function ($test) {
            $channel = Channel::factory()
                ->public()
                ->create();

            return [
                jsonApi('GET', "/api/channels/$channel->id"),
                'data.attributes.extendTest',
                (string) $channel->getKey(),
            ];
        },
    ],
    'comments' => [
        Extend\Api\CommentsResource::class,
        function ($test) {
            $comment = Comment::factory()
                ->for(Post::factory()->for(Channel::factory()->public()))
                ->create();

            return [
                jsonApi('GET', "/api/comments/$comment->id"),
                'data.attributes.extendTest',
                (string) $comment->getKey(),
            ];
        },
    ],
    'groups' => [
        Extend\Api\GroupsResource::class,
        function ($test) {
            $group = Group::create(['name' => 'Test Group', 'is_public' => true]);

            return [
                jsonApi('GET', "/api/groups/$group->id"),
                'data.attributes.extendTest',
                (string) $group->getKey(),
            ];
        },
    ],
    'pages' => [
        Extend\Api\PagesResource::class,
        function ($test) {
            $page = Page::factory()
                ->public()
                ->create();

            return [
                jsonApi('GET', "/api/pages/$page->id"),
                'data.attributes.extendTest',
                (string) $page->getKey(),
            ];
        },
    ],
    'posts' => [
        Extend\Api\PostsResource::class,
        function ($test) {
            $post = Post::factory()
                ->for(Channel::factory()->public())
                ->create();

            return [
                jsonApi('GET', "/api/posts/$post->id"),
                'data.attributes.extendTest',
                (string) $post->getKey(),
            ];
        },
    ],
    'structure headings' => [
        Extend\Api\StructureHeadingsResource::class,
        function ($test) {
            $heading = StructureHeading::create(['name' => 'Test Heading']);

            return [
                jsonApi('GET', "/api/structureHeadings/$heading->id"),
                'data.attributes.extendTest',
                (string) $heading->getKey(),
            ];
        },
    ],
    'structure links' => [
        Extend\Api\StructureLinksResource::class,
        function ($test) {
            $link = StructureLink::create(['name' => 'Test Link', 'href' => 'https://example.com']);
            $link->savePermissions(['group:1' => ['view' => true]]);

            return [
                jsonApi('GET', "/api/structureLinks/$link->id"),
                'data.attributes.extendTest',
                (string) $link->getKey(),
            ];
        },
    ],
    'structure' => [
        Extend\Api\StructureResource::class,
        function ($test) {
            $heading = StructureHeading::create(['name' => 'Test Heading']);
            $structure = $heading->structure;

            return [
                jsonApi('GET', '/api/structure'),
                'data.0.attributes.extendTest',
                (string) $structure->getKey(),
            ];
        },
    ],
    'users' => [
        Extend\Api\UsersResource::class,
        function ($test) {
            $user = User::factory()->create();

            return [
                jsonApi('GET', "/api/users/$user->id"),
                'data.attributes.extendTest',
                (string) $user->getKey(),
            ];
        },
    ],
]);

dataset('api resources with relations', [
    'channel users' => [
        Extend\Api\ChannelUsersResource::class,
        function ($test) {
            $user = User::factory()->create();
            $channel = Channel::factory()
                ->public()
                ->create();
            $channelUser = ChannelUser::create([
                'channel_id' => $channel->id,
                'user_id' => $user->id,
                'notifications' => 'follow',
            ]);

            $test->actingAs($user);

            return [
                jsonApi('GET', "/api/channels/$channel->id?include=userState"),
                'channelUsers',
                $channelUser->getKey(),
            ];
        },
    ],
    'post users' => [
        Extend\Api\PostUsersResource::class,
        function ($test) {
            $user = User::factory()->create();
            $post = Post::factory()
                ->for(Channel::factory()->public())
                ->create();
            $postUser = PostUser::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'notifications' => 'follow',
            ]);

            $test->actingAs($user);

            return [
                jsonApi('GET', "/api/posts/$post->id?include=userState"),
                'postUsers',
                $postUser->getKey(),
            ];
        },
    ],
    'reaction sets' => [
        Extend\Api\ReactionSetsResource::class,
        function ($test) {
            $reactionSet = ReactionSet::create(['name' => 'Test Reaction Set']);
            ReactionType::create([
                'reaction_set_id' => $reactionSet->id,
                'name' => 'Test Reaction Type',
                'score' => 1,
                'position' => 0,
            ]);

            $channel = Channel::factory()
                ->public()
                ->create(['posts_reaction_set_id' => $reactionSet->id]);

            return [
                jsonApi('GET', "/api/channels/$channel->id?include=postsReactionSet"),
                'reactionSets',
                $reactionSet->getKey(),
            ];
        },
    ],
    'reaction types' => [
        Extend\Api\ReactionTypesResource::class,
        function ($test) {
            $reactionSet = ReactionSet::create(['name' => 'Test Reaction Set']);
            $reactionType = ReactionType::create([
                'reaction_set_id' => $reactionSet->id,
                'name' => 'Test Reaction Type',
                'score' => 1,
                'position' => 0,
            ]);

            $channel = Channel::factory()
                ->public()
                ->create(['posts_reaction_set_id' => $reactionSet->id]);

            return [
                jsonApi('GET', "/api/channels/$channel->id?include=postsReactionSet.reactionTypes"),
                'reactionTypes',
                $reactionType->getKey(),
            ];
        },
    ],
    'reactions' => [
        Extend\Api\ReactionsResource::class,
        function ($test) {
            $user = User::factory()->create();
            $post = Post::factory()
                ->for(Channel::factory()->public())
                ->create();
            $reactionSet = ReactionSet::create(['name' => 'Test Reaction Set']);
            $reactionType = ReactionType::create([
                'reaction_set_id' => $reactionSet->id,
                'name' => 'Test Reaction Type',
                'score' => 1,
                'position' => 0,
            ]);
            $reaction = Reaction::create([
                'user_id' => $user->id,
                'reaction_type_id' => $reactionType->id,
                'content_id' => $post->id,
                'content_type' => $post->getMorphClass(),
            ]);

            return [
                jsonApi('GET', "/api/posts/$post->id?include=reactions"),
                'reactions',
                $reaction->getKey(),
            ];
        },
    ],
    'taxonomies' => [
        Extend\Api\TaxonomiesResource::class,
        function ($test) {
            $taxonomy = Taxonomy::create(['name' => 'Test Taxonomy']);
            $taxonomy->savePermissions(['group:1' => ['view' => true]]);
            Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Test Tag']);

            $channel = Channel::factory()
                ->public()
                ->create();
            $channel->taxonomies()->attach($taxonomy);

            return [
                jsonApi('GET', "/api/channels/$channel->id?include=taxonomies.tags"),
                'taxonomies',
                $taxonomy->getKey(),
            ];
        },
    ],
    'tags' => [
        Extend\Api\TagsResource::class,
        function ($test) {
            $taxonomy = Taxonomy::create(['name' => 'Test Taxonomy']);
            $taxonomy->savePermissions(['group:1' => ['view' => true]]);
            $tag = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Test Tag']);

            $channel = Channel::factory()
                ->public()
                ->create();
            $channel->taxonomies()->attach($taxonomy);

            return [
                jsonApi('GET', "/api/channels/$channel->id?include=taxonomies.tags"),
                'tags',
                $tag->getKey(),
            ];
        },
    ],
]);

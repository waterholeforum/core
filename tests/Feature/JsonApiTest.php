<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Waterhole\Database\Seeders\DefaultSeeder;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Group;
use Waterhole\Models\Page;
use Waterhole\Models\Post;
use Waterhole\Models\ReactionType;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;
use Waterhole\Models\Tag;
use Waterhole\Models\Taxonomy;
use Waterhole\Models\User;
use Waterhole\Providers\RouteServiceProvider;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('api/channels', function () {
    test('retrieve channel', function () {
        $channel = Channel::factory()
            ->public()
            ->create();

        $response = jsonApi('GET', "/api/channels/$channel->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'type' => 'channels',
                'id' => $channel->id,
                'attributes' => ['name' => $channel->name, 'url' => $channel->url],
            ],
        ]);
    });

    test('retrieve channel user state', function () {
        $this->actingAs(User::factory()->create());

        $channel = Channel::factory()
            ->public()
            ->create();

        $response = jsonApi('GET', "/api/channels/$channel->id?include=userState");

        $response->assertOk();
        $response->assertJson([
            'included' => [['type' => 'channelUsers', 'attributes' => ['notifications' => null]]],
        ]);
    });
});

describe('api/comments', function () {
    test('list comments', function () {
        Post::factory()
            ->for(Channel::factory()->public())
            ->has(Comment::factory(2))
            ->create();

        $response = jsonApi('GET', '/api/comments');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    });

    test('retrieve comment', function () {
        $comment = Comment::factory()
            ->for(Post::factory()->for(Channel::factory()->public()))
            ->create();

        $response = jsonApi('GET', "/api/comments/$comment->id");

        $response->assertOk();
        $response->assertJson(['data' => ['type' => 'comments', 'id' => $comment->id]]);
    });
});

describe('api/groups', function () {
    test('list groups', function () {
        $response = jsonApi('GET', '/api/groups');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    });

    test('retrieve group', function () {
        $group = Group::custom()->firstOrFail();

        $response = jsonApi('GET', "/api/groups/$group->id");

        $response->assertOk();
        $response->assertJson(['data' => ['type' => 'groups', 'id' => $group->id]]);
    });
});

describe('api/pages', function () {
    test('retrieve page', function () {
        $page = Page::factory()
            ->public()
            ->create();

        $response = jsonApi('GET', "/api/pages/$page->id");

        $response->assertOk();
        $response->assertJson(['data' => ['type' => 'pages', 'id' => $page->id]]);
    });
});

describe('api/posts', function () {
    test('list posts', function () {
        Post::factory(2)
            ->for(Channel::factory()->public())
            ->create();

        $response = jsonApi('GET', '/api/posts?include=channel');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonCount(1, 'included');
    });

    test('retrieve post', function () {
        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create();

        $response = jsonApi('GET', "/api/posts/$post->id");

        $response->assertOk();
        $response->assertJson(['data' => ['type' => 'posts', 'id' => $post->id]]);
    });

    test('retrieve post user state', function () {
        $this->actingAs(User::factory()->create());

        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create();

        $response = jsonApi('GET', "/api/posts/$post->id?include=userState");

        $response->assertOk();
        $response->assertJson([
            'included' => [['type' => 'postUsers', 'attributes' => ['notifications' => null]]],
        ]);
    });

    test('retrieve post tags and taxonomies', function () {
        $admin = User::factory()->create();
        $admin->groups()->attach(Group::ADMIN_ID);
        $this->actingAs($admin);

        $taxonomy = Taxonomy::create(['name' => 'Topics', 'allow_multiple' => true]);
        $tag = Tag::create(['name' => 'Feature', 'taxonomy_id' => $taxonomy->id]);

        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create();

        $post->tags()->attach($tag);

        $response = jsonApi('GET', "/api/posts/$post->id?include=tags,tags.taxonomy");

        $response->assertOk();
        $response->assertJsonFragment(['type' => 'tags', 'id' => (string) $tag->id]);
        $response->assertJsonFragment(['type' => 'taxonomies', 'id' => (string) $taxonomy->id]);
    });

    test('retrieve post reactions and reaction counts', function () {
        $this->seed(DefaultSeeder::class);

        $user = User::factory()->create();
        $reactionType = ReactionType::firstOrFail();

        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create();

        $reaction = $post->reactions()->create([
            'reaction_type_id' => $reactionType->id,
            'user_id' => $user->id,
        ]);

        $response = jsonApi(
            'GET',
            "/api/posts/$post->id?include=reactions,reactionCounts,reactionCounts.reactionType",
        );

        $response->assertOk();
        $response->assertJsonFragment(['type' => 'reactions', 'id' => (string) $reaction->id]);
        $response->assertJsonFragment(['type' => 'reactionCounts']);
        $response->assertJsonFragment([
            'type' => 'reactionTypes',
            'id' => (string) $reactionType->id,
        ]);
    });
});

describe('api/structure', function () {
    test('list structure', function () {
        Channel::factory()
            ->public()
            ->create();

        Page::factory()
            ->public()
            ->create();

        $response = jsonApi('GET', '/api/structure?include=content');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonCount(2, 'included');
    });
});

describe('api/structure headings and links', function () {
    test('retrieve structure heading', function () {
        $heading = StructureHeading::create(['name' => 'Heading']);

        $response = jsonApi('GET', "/api/structureHeadings/$heading->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'type' => 'structureHeadings',
                'id' => $heading->id,
            ],
        ]);
    });

    test('retrieve structure link', function () {
        $link = StructureLink::create([
            'name' => 'Waterhole',
            'href' => 'https://waterhole.dev',
        ]);

        $link->savePermissions(['group:1' => ['view' => true]]);

        $response = jsonApi('GET', "/api/structureLinks/$link->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'type' => 'structureLinks',
                'id' => $link->id,
            ],
        ]);
    });
});

describe('api/users', function () {
    test('list users', function () {
        User::factory(2)->create();

        $response = jsonApi('GET', '/api/users');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    });

    test('retrieve user', function () {
        $user = User::factory()->create();

        $response = jsonApi('GET', "/api/users/$user->id");

        $response->assertOk();
        $response->assertJson(['data' => ['type' => 'users', 'id' => $user->id]]);
    });
});

describe('api config', function () {
    test('disables api routes when disabled', function () {
        $originalRoutes = Route::getRoutes();
        $originalConfig = config()->get('waterhole.api');

        try {
            config(['waterhole.api.enabled' => false]);

            Route::setRoutes(new RouteCollection());
            app()->register(RouteServiceProvider::class, true);

            $this->get('/api/posts')->assertNotFound();
        } finally {
            Route::setRoutes($originalRoutes);
            config(['waterhole.api' => $originalConfig]);
        }
    });

    test('uses configured api path', function () {
        $originalRoutes = Route::getRoutes();
        $originalConfig = config()->get('waterhole.api');

        try {
            config([
                'waterhole.api.enabled' => true,
                'waterhole.api.path' => 'v2',
            ]);

            Route::setRoutes(new RouteCollection());
            app()->register(RouteServiceProvider::class, true);

            jsonApi('GET', '/api/posts')->assertNotFound();
            jsonApi('GET', '/v2/posts')->assertOk();
        } finally {
            Route::setRoutes($originalRoutes);
            config(['waterhole.api' => $originalConfig]);
        }
    });

    test('applies public api middleware settings', function () {
        $originalRoutes = Route::getRoutes();
        $originalConfig = config()->get('waterhole.api');

        try {
            config([
                'waterhole.api.enabled' => true,
                'waterhole.api.public' => false,
            ]);

            Route::setRoutes(new RouteCollection());
            app()->register(RouteServiceProvider::class, true);

            $response = jsonApi('GET', '/api/posts');

            expect($response->getStatusCode())->toBeIn([401, 403]);
        } finally {
            Route::setRoutes($originalRoutes);
            config(['waterhole.api' => $originalConfig]);
        }
    });

    test('supports sanctum token auth', function () {
        $originalRoutes = Route::getRoutes();
        $originalConfig = config()->get('waterhole.api');

        try {
            config([
                'waterhole.api.enabled' => true,
                'waterhole.api.public' => false,
            ]);

            Route::setRoutes(new RouteCollection());
            app()->register(RouteServiceProvider::class, true);

            Sanctum::actingAs(User::factory()->create(), ['waterhole']);

            jsonApi('GET', '/api/posts')->assertOk();
        } finally {
            Route::setRoutes($originalRoutes);
            config(['waterhole.api' => $originalConfig]);
        }
    });
});

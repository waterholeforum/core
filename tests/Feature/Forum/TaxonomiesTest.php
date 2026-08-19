<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Actions\EditTags;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Models\Tag;
use Waterhole\Models\Taxonomy;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('taxonomy assignment', function () {
    test('assigns tags to post', function () {
        $channel = Channel::factory()->public()->create();
        $taxonomy = Taxonomy::create(['name' => 'Topics', 'allow_multiple' => true]);
        $taxonomy->savePermissions(['group:1' => ['view' => true, 'assign-tags' => true]]);
        $tag = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Feature']);
        $channel->taxonomies()->attach($taxonomy);

        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->post(route('waterhole.posts.store'), [
                'channel_id' => $channel->id,
                'title' => 'Tagged post',
                'body' => 'Body',
                'tag_ids' => [
                    $taxonomy->id => [$tag->id],
                ],
                'commit' => true,
            ])
            ->assertRedirect();

        $post = Post::where('title', 'Tagged post')->firstOrFail();

        expect($post->tags->modelKeys())->toContain($tag->id);
    });

    test('rejects multiple tags for a single-tag taxonomy', function () {
        $channel = Channel::factory()->public()->create();
        $taxonomy = Taxonomy::create(['name' => 'Single', 'allow_multiple' => false]);
        $taxonomy->savePermissions(['group:1' => ['view' => true, 'assign-tags' => true]]);
        $tagA = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'A']);
        $tagB = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'B']);
        $channel->taxonomies()->attach($taxonomy);

        $this
            ->actingAs(User::factory()->create())
            ->post(route('waterhole.posts.store'), [
                'channel_id' => $channel->id,
                'title' => 'Invalid tags',
                'body' => 'Body',
                'tag_ids' => [$taxonomy->id => [$tagA->id, $tagB->id]],
                'commit' => true,
            ])
            ->assertSessionHasErrors("tag_ids.$taxonomy->id");
    });
});

describe('tag editing', function () {
    test('updates assignable tags without changing inaccessible tags', function () {
        $channel = Channel::factory()->public()->create();
        $assignable = Taxonomy::create(['name' => 'Topics', 'allow_multiple' => true]);
        $assignable->savePermissions(['group:2' => ['view' => true, 'assign-tags' => true]]);
        $inaccessible = Taxonomy::create(['name' => 'Internal']);
        $inaccessible->savePermissions(['group:3' => ['view' => true, 'assign-tags' => true]]);
        $channel->taxonomies()->attach([$assignable->id, $inaccessible->id]);
        $oldTag = Tag::create(['taxonomy_id' => $assignable->id, 'name' => 'Old']);
        $newTag = Tag::create(['taxonomy_id' => $assignable->id, 'name' => 'New']);
        $preservedTag = Tag::create(['taxonomy_id' => $inaccessible->id, 'name' => 'Secret']);
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->for($user)->create();
        $post->tags()->attach([$oldTag->id, $preservedTag->id]);

        $this
            ->actingAs($user)
            ->withHeader('Accept', 'text/vnd.turbo-stream.html')
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => EditTags::class,
                'confirmed' => true,
                'tag_ids' => [$assignable->id => [$newTag->id]],
            ])
            ->assertOk()
            ->assertSeeText('New')
            ->assertDontSeeText('Old');

        expect(
            $post->tags()->withoutGlobalScopes()->pluck('tags.id')->all(),
        )->toEqualCanonicalizing([$newTag->id, $preservedTag->id]);
    });

    test('requires edit permission', function () {
        $channel = Channel::factory()->public()->create();
        $taxonomy = Taxonomy::create(['name' => 'Topics']);
        $taxonomy->savePermissions(['group:2' => ['view' => true, 'assign-tags' => true]]);
        Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Tag']);
        $channel->taxonomies()->attach($taxonomy);
        $post = Post::factory()->for($channel)->for(User::factory())->create();

        $this
            ->actingAs(User::factory()->create())
            ->get(route('waterhole.actions.create', [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => EditTags::class,
            ]))
            ->assertForbidden();
    });
});

describe('channel visibility', function () {
    test('hides tags in private taxonomies from guests', function () {
        $channel = Channel::factory()->public()->create();
        $taxonomy = Taxonomy::create(['name' => 'Private']);
        $tag = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Secret']);
        $channel->taxonomies()->attach($taxonomy);

        $post = Post::factory()->for($channel)->create(['title' => 'Private tags post']);
        $post->tags()->attach($tag);

        $this->get($post->url)->assertOk()->assertDontSeeText('Secret');
    });

    test('shows tags to users with permission', function () {
        $channel = Channel::factory()->public()->create();
        $taxonomy = Taxonomy::create(['name' => 'Members']);
        $taxonomy->savePermissions(['group:2' => ['view' => true]]);
        $tag = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'VisibleTag']);
        $channel->taxonomies()->attach($taxonomy);

        $post = Post::factory()->for($channel)->create(['title' => 'Visible tags post']);
        $post->tags()->attach($tag);

        $user = User::factory()->create();

        $this->actingAs($user)->get($post->url)->assertOk()->assertSeeText('VisibleTag');
    });
});

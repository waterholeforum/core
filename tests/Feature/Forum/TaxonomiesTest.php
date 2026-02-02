<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->actingAs($user)
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

    test('enforces single-tag taxonomy rules', function () {
        $channel = Channel::factory()->public()->create();
        $taxonomy = Taxonomy::create(['name' => 'Single', 'allow_multiple' => false]);
        $taxonomy->savePermissions(['group:1' => ['view' => true, 'assign-tags' => true]]);
        $tagA = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'A']);
        $channel->taxonomies()->attach($taxonomy);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('waterhole.posts.store'), [
                'channel_id' => $channel->id,
                'title' => 'Single taxonomy post',
                'body' => 'Body',
                'tag_ids' => [
                    $taxonomy->id => [$tagA->id],
                ],
                'commit' => true,
            ])
            ->assertRedirect();

        $post = Post::where('title', 'Single taxonomy post')->firstOrFail();
        expect($post->tags)->toHaveCount(1);
    });
});

describe('channel visibility', function () {
    test('hides tags in private taxonomies from guests', function () {
        $channel = Channel::factory()->public()->create();
        $taxonomy = Taxonomy::create(['name' => 'Private']);
        $tag = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Secret']);
        $channel->taxonomies()->attach($taxonomy);

        $post = Post::factory()
            ->for($channel)
            ->create(['title' => 'Private tags post']);
        $post->tags()->attach($tag);

        $this->get($post->url)->assertOk()->assertDontSeeText('Secret');
    });

    test('shows tags to users with permission', function () {
        $channel = Channel::factory()->public()->create();
        $taxonomy = Taxonomy::create(['name' => 'Members']);
        $taxonomy->savePermissions(['group:2' => ['view' => true]]);
        $tag = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'VisibleTag']);
        $channel->taxonomies()->attach($taxonomy);

        $post = Post::factory()
            ->for($channel)
            ->create(['title' => 'Visible tags post']);
        $post->tags()->attach($tag);

        $user = User::factory()->create();

        $this->actingAs($user)->get($post->url)->assertOk()->assertSeeText('VisibleTag');
    });
});

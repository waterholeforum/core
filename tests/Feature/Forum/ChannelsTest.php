<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Filters\Oldest;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Models\Tag;
use Waterhole\Models\Taxonomy;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('taxonomy visibility', function () {
    test('private channel not visible to guests', function () {
        $channel = Channel::factory()->create();

        $this->get($channel->url)->assertNotFound();
    });

    test('private channel visible to members with permission', function () {
        $channel = Channel::factory()->create();
        $channel->savePermissions([
            'group:2' => ['view' => true],
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)->get($channel->url)->assertOk();
    });
});

describe('channel feeds', function () {
    test('channel feed shows posts with permissions applied', function () {
        $channel = Channel::factory()->public()->create();
        $viewer = User::factory()->create();

        Post::factory()->for($channel)->create([
            'title' => 'Visible channel post',
        ]);

        $hidden = Post::factory()->for($channel)->create([
            'title' => 'Hidden channel post',
            'user_id' => User::factory()->create()->id,
        ]);

        $hidden->update(['deleted_by' => $hidden->user_id]);
        $hidden->delete();

        $this
            ->actingAs($viewer)
            ->get($channel->url)
            ->assertOk()
            ->assertSeeText('Visible channel post')
            ->assertDontSeeText('Hidden channel post');
    });

    test('channel feed honors configured filters', function () {
        $channel = Channel::factory()->public()->create();
        $channel->update(['filters' => [Oldest::class]]);

        Post::factory()->for($channel)->create([
            'title' => 'Older post',
            'created_at' => now()->subDay(),
            'last_activity_at' => now()->subDay(),
        ]);

        Post::factory()->for($channel)->create([
            'title' => 'Newer post',
            'created_at' => now(),
            'last_activity_at' => now(),
        ]);

        $this->get($channel->url)->assertOk()->assertSeeInOrder(['Older post', 'Newer post']);
    });

    test('filters by tags', function () {
        $channel = Channel::factory()->public()->create();
        $topics = Taxonomy::create(['name' => 'Topics']);
        $types = Taxonomy::create(['name' => 'Types']);
        foreach ([$topics, $types] as $taxonomy) {
            $taxonomy->savePermissions(['group:1' => ['view' => true]]);
        }
        $channel->taxonomies()->attach([$topics->id, $types->id]);
        $alpha = Tag::create(['taxonomy_id' => $topics->id, 'name' => 'Alpha']);
        $beta = Tag::create(['taxonomy_id' => $topics->id, 'name' => 'Beta']);
        $guide = Tag::create(['taxonomy_id' => $types->id, 'name' => 'Guide']);

        $matchingA = Post::factory()->for($channel)->create(['title' => 'Alpha guide']);
        $matchingA->tags()->attach([$alpha->id, $guide->id]);
        $matchingB = Post::factory()->for($channel)->create(['title' => 'Beta guide']);
        $matchingB->tags()->attach([$beta->id, $guide->id]);
        $wrongType = Post::factory()->for($channel)->create(['title' => 'Alpha only']);
        $wrongType->tags()->attach($alpha);

        $this
            ->get(route('waterhole.channels.show', [
                'channel' => $channel,
                'tags' => [
                    $topics->id => [$alpha->id, $beta->id],
                    $types->id => [$guide->id],
                ],
            ]))
            ->assertOk()
            ->assertSeeText('Alpha guide')
            ->assertSeeText('Beta guide')
            ->assertDontSeeText('Alpha only');
    });

    test('rejects invalid tag filters', function () {
        $channel = Channel::factory()->public()->create();
        $taxonomy = Taxonomy::create(['name' => 'Topics']);
        $otherTaxonomy = Taxonomy::create(['name' => 'Other']);
        foreach ([$taxonomy, $otherTaxonomy] as $item) {
            $item->savePermissions(['group:1' => ['view' => true]]);
        }
        $channel->taxonomies()->attach($taxonomy);
        $otherTag = Tag::create(['taxonomy_id' => $otherTaxonomy->id, 'name' => 'Other tag']);

        $this->get(route('waterhole.channels.show', [
            'channel' => $channel,
            'tags' => [$otherTaxonomy->id => [$otherTag->id]],
        ]))->assertNotFound();

        $this->get(route('waterhole.channels.show', [
            'channel' => $channel,
            'tags' => [$taxonomy->id => [$otherTag->id]],
        ]))->assertNotFound();
    });
});

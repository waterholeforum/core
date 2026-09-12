<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;

uses(RefreshDatabase::class);

test('comments reuse their post when queried, paginated, or eager loaded', function () {
    $this->seed(GroupsSeeder::class);

    $post = Post::factory()->for(Channel::factory()->public())->create();
    Comment::factory()->for($post)->create();

    $comments = [
        $post->comments()->getQuery()->first(),
        (clone $post->comments()->getQuery())->withOnly('user')->first(),
        $post->comments()->paginate()->first(),
        $post->load('comments')->comments->first(),
        $post->load('lastComment')->lastComment,
    ];

    $this->expectsDatabaseQueryCount(0);

    foreach ($comments as $comment) {
        expect($comment->post)->toBe($post);
    }

    // Hydrating both directions must still allow model serialization.
    expect($post->toArray()['comments'])->toHaveCount(1);
});

test('comment hydration shares the post through loaded parents and descendants', function () {
    $post = new Post();
    $parent = new Comment();
    $comment = new Comment();
    $child = new Comment();
    $grandchild = new Comment();

    $comment->setRelation('parent', $parent);
    $comment->setRelation('children', $comment->newCollection([$child]));
    $child->setRelation('parent', $comment);
    $child->setRelation('children', $child->newCollection([$grandchild]));

    $this->expectsDatabaseQueryCount(0);

    expect($comment->hydratePostRelation($post))->toBe($comment);

    foreach ([$parent, $comment, $child, $grandchild] as $related) {
        expect($related->post)->toBe($post);
    }
});

test('comment hydration leaves unloaded relationships untouched', function () {
    $post = new Post();
    $comment = new Comment();

    $this->expectsDatabaseQueryCount(0);

    $comment->hydratePostRelation($post);

    expect($comment->post)->toBe($post);
    expect($comment->relationLoaded('parent'))->toBeFalse();
    expect($comment->relationLoaded('children'))->toBeFalse();
});

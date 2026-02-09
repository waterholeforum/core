<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);

    config([
        'waterhole.forum.name' => 'Waterhole',
        'waterhole.seo.default_description' => 'Default SEO description.',
        'waterhole.seo.default_og_image' => 'https://example.com/default-og.png',
    ]);
});

test('home page includes seo tags', function () {
    Channel::factory()->public()->create();

    $this->get('/')
        ->assertSeeHtml('<meta name="description" content="Default SEO description." />')
        ->assertSeeHtml('<meta property="og:type" content="website" />')
        ->assertSeeHtml('"@type":"WebSite"');
});

test('post page includes seo tags', function () {
    $user = User::factory()->create();
    $post = Post::factory()
        ->for(Channel::factory()->public())
        ->for($user)
        ->create([
            'title' => 'SEO Post Title',
            'body' => 'SEO body text for description.',
        ]);
    Comment::factory()->for($post)->for($user)->create();

    $description = \Illuminate\Support\Str::limit($post->body_text ?? '', 160);

    $this->get($post->url)
        ->assertSeeHtml('<meta property="og:title" content="SEO Post Title - Waterhole" />')
        ->assertSeeHtml('<meta name="description" content="' . e($description) . '" />')
        ->assertSeeHtml('<meta property="og:image" content="https://example.com/default-og.png" />')
        ->assertSee('itemtype="https://schema.org/DiscussionForumPosting"', false)
        ->assertSee('itemprop="headline"', false)
        ->assertSee('itemprop="text"', false)
        ->assertSee('itemprop="author"', false)
        ->assertSee('itemtype="https://schema.org/Comment"', false)
        ->assertDontSee('"@type":"DiscussionForumPosting"', false);
});

test('standalone comment page includes discussion schema fields', function () {
    $postAuthor = User::factory()->create();
    $commentAuthor = User::factory()->create();
    $post = Post::factory()
        ->for(Channel::factory()->public())
        ->for($postAuthor)
        ->create(['title' => 'SEO Post Title']);
    $comment = Comment::factory()
        ->for($post)
        ->for($commentAuthor)
        ->create(['body' => 'SEO comment body text.']);

    $this->get($comment->url)
        ->assertSee('itemtype="https://schema.org/DiscussionForumPosting"', false)
        ->assertSee('itemprop="headline"', false)
        ->assertSee('itemprop="text"', false)
        ->assertSee('itemprop="author"', false)
        ->assertSee('itemtype="https://schema.org/Comment"', false)
        ->assertDontSee('"@type":"DiscussionForumPosting"', false);
});

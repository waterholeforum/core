<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

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
    $post = Post::factory()
        ->for(Channel::factory()->public())
        ->create([
            'title' => 'SEO Post Title',
            'body' => 'SEO body text for description.',
        ]);

    $description = \Illuminate\Support\Str::limit($post->body_text ?? '', 160);

    $this->get($post->url)
        ->assertSeeHtml('<meta property="og:title" content="SEO Post Title - Waterhole" />')
        ->assertSeeHtml('<meta name="description" content="' . e($description) . '" />')
        ->assertSeeHtml('<meta property="og:image" content="https://example.com/default-og.png" />')
        ->assertSeeHtml('"@type":"DiscussionForumPosting"');
});

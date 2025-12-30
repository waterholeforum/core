<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Group;
use Waterhole\Models\Post;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('Ui extenders', function () {
    test('extend index page components', function () {
        extend(function (Extend\Ui\IndexPage $index) {
            $index->sidebar->add(content: new HtmlString('Extend Index Sidebar'));
            $index->footer->add(content: new HtmlString('Extend Index Footer'));
        });

        extend(function (Extend\Ui\PostFeed $feed) {
            $feed->header->add(content: new HtmlString('Extend Feed Header'));
            $feed->toolbar->add(content: new HtmlString('Extend Feed Toolbar'));
        });

        extend(function (Extend\Ui\PostListItem $items) {
            $items->info->add(content: new HtmlString('Extend List Info'));
            $items->secondary->add(content: new HtmlString('Extend List Secondary'));
        });

        extend(function (Extend\Ui\PostAttributes $attributes) {
            $attributes->add(fn(Post $post) => ['data-marker' => 'extend-post-attr']);
        });

        extend(function (Extend\Ui\DocumentHead $head) {
            $head->add(content: new HtmlString('<meta name="extend-test" content="1">'));
        });

        extend(function (Extend\Ui\UserMenu $menu) {
            $menu->add(content: new HtmlString('Extend User Menu'));
        });

        $channel = Channel::factory()
            ->public()
            ->create();

        Post::factory()
            ->for($channel)
            ->create(['title' => 'Index Post']);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/')
            ->assertSeeText('Extend Index Sidebar')
            ->assertSeeText('Extend Index Footer')
            ->assertSeeText('Extend Feed Header')
            ->assertSeeText('Extend Feed Toolbar')
            ->assertSeeText('Extend List Info')
            ->assertSeeText('Extend List Secondary')
            ->assertSeeHtml('data-marker="extend-post-attr"')
            ->assertSeeHtml('<meta name="extend-test" content="1">')
            ->assertSeeText('Extend User Menu');
    });

    test('extend post page components', function () {
        extend(function (Extend\Ui\PostPage $page) {
            $page->header->add(content: new HtmlString('Extend Post Header'));
            $page->sidebar->add(content: new HtmlString('Extend Post Sidebar'));
            $page->middle->add(content: new HtmlString('Extend Post Middle'));
            $page->bottom->add(content: new HtmlString('Extend Post Bottom'));
        });

        extend(function (Extend\Ui\PostFooter $footer) {
            $footer->add(content: new HtmlString('Extend Post Footer'));
        });

        extend(function (Extend\Ui\CommentComponent $comments) {
            $comments->header->add(content: new HtmlString('Extend Comment Header'));
            $comments->footer->add(content: new HtmlString('Extend Comment Footer'));
            $comments->buttons->add(content: new HtmlString('Extend Comment Buttons'));
        });

        extend(function (Extend\Ui\CommentAttributes $attributes) {
            $attributes->add(
                fn(Comment $comment) => ['data-comment-marker' => 'extend-comment-attr'],
            );
        });

        extend(function (Extend\Ui\PostAttributes $attributes) {
            $attributes->add(fn(Post $post) => ['data-post-marker' => 'extend-post-page-attr']);
        });

        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->has(Comment::factory())
            ->create();

        $this->get(URL::route('waterhole.posts.show', $post))
            ->assertSeeText('Extend Post Header')
            ->assertSeeText('Extend Post Sidebar')
            ->assertSeeText('Extend Post Middle')
            ->assertSeeText('Extend Post Bottom')
            ->assertSeeText('Extend Post Footer')
            ->assertSeeText('Extend Comment Header')
            ->assertSeeText('Extend Comment Footer')
            ->assertSeeText('Extend Comment Buttons')
            ->assertSeeHtml('data-comment-marker="extend-comment-attr"')
            ->assertSeeHtml('data-post-marker="extend-post-page-attr"');
    });

    test('extend user profile components', function () {
        extend(function (Extend\Ui\UserNav $nav) {
            $nav->add(content: new HtmlString('Extend User Nav'));
        });

        extend(function (Extend\Ui\UserInfo $info) {
            $info->add(content: new HtmlString('Extend User Info'));
        });

        $user = User::factory()->create();

        $this->get(URL::route('waterhole.user.posts', $user))
            ->assertSeeText('Extend User Nav')
            ->assertSeeText('Extend User Info');
    });

    test('extend text editor components', function () {
        extend(function (Extend\Ui\TextEditor $editor) {
            $editor->add(content: new HtmlString('Extend Text Editor'));
        });

        $channel = Channel::factory()
            ->public()
            ->create();

        $admin = User::factory()->create();
        $admin->groups()->attach(Group::ADMIN_ID);

        $this->actingAs($admin)
            ->get(URL::route('waterhole.posts.create', ['channel_id' => $channel->id]))
            ->assertSeeText('Extend Text Editor');
    });

    test('extend cp components', function () {
        extend(function (Extend\Ui\CpNav $nav) {
            $nav->add(content: new HtmlString('Extend Cp Nav'));
        });

        extend(function (Extend\Ui\CpAlerts $alerts) {
            $alerts->add(content: new HtmlString('Extend Cp Alert'));
        });

        $admin = User::factory()->create();
        $admin->groups()->attach(Group::ADMIN_ID);

        $this->actingAs($admin)
            ->get(URL::route('waterhole.cp.dashboard'))
            ->assertSeeText('Extend Cp Nav')
            ->assertSeeText('Extend Cp Alert');
    });

    test('extend login page components', function () {
        extend(function (Extend\Ui\LoginPage $login) {
            $login->add(content: new HtmlString('Extend Login Page'));
        });

        $this->get('login')->assertSeeText('Extend Login Page');
    });

    test('extend preferences components', function () {
        $marker = 'Extend Test Preference';

        extend(function (Extend\Ui\Preferences $preferences) use ($marker) {
            $preferences->account->add('extend-test', new HtmlString($marker));
        });

        $this->actingAs(User::factory()->create())
            ->get(URL::route('waterhole.preferences.account'))
            ->assertSeeText($marker);
    });
});

describe('Layout extender', function () {
    test('add header component', function () {
        extend(function (Extend\Ui\Layout $layout) {
            $layout->header->add(content: new HtmlString('hello world'));
        });

        $this->get('login')->assertSeeText('hello world');
    });

    test('add before component', function () {
        extend(function (Extend\Ui\Layout $layout) {
            $layout->before->add(content: new HtmlString('hello world'));
        });

        $this->get('login')->assertSeeText('hello world');
    });

    test('add after component', function () {
        extend(function (Extend\Ui\Layout $layout) {
            $layout->after->add(content: new HtmlString('hello world'));
        });

        $this->get('login')->assertSeeText('hello world');
    });
});

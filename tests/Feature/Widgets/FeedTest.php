<?php

declare(strict_types=1);

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Widgets\Feed;

uses(RefreshDatabase::class);

function widgetFeedXml(string $title): string
{
    return <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <rss version="2.0">
            <channel>
                <title>{$title}</title>
                <link>https://example.com</link>
                <description>Example feed</description>
            </channel>
        </rss>
        XML;
}

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
    Http::preventStrayRequests();

    // Exercise Waterhole's deferred middleware without relying on a host
    // application having adopted Laravel 11's default global middleware.
    $kernel = app(Kernel::class);
    $kernel->setGlobalMiddleware(array_values(array_diff($kernel->getGlobalMiddleware(), [
        InvokeDeferredCallbacks::class,
    ])));

    Route::get('/feed-cache-test', fn() => (new Feed(
        'https://example.com/feed.xml',
    ))->feed->getTitle())->middleware('waterhole.web');
});

test('feeds reuse fresh content and refresh stale content after the response', function () {
    Http::fakeSequence('example.com/feed.xml')
        ->push(widgetFeedXml('Original Feed'))
        ->push(widgetFeedXml('Updated Feed'));

    $this->get('/feed-cache-test')->assertOk()->assertSeeText('Original Feed');
    $this->travel(5)->hours();
    $this->get('/feed-cache-test')->assertOk()->assertSeeText('Original Feed');
    Http::assertSentCount(1);

    $this->travel(2)->hours();
    $this->get('/feed-cache-test')->assertOk()->assertSeeText('Original Feed');
    Http::assertSentCount(2);

    $this->get('/feed-cache-test')->assertOk()->assertSeeText('Updated Feed');
    Http::assertSentCount(2);
});

test('expired feeds are fetched before rendering', function () {
    Http::fakeSequence('example.com/feed.xml')
        ->push(widgetFeedXml('Original Feed'))
        ->push(widgetFeedXml('Updated Feed'));

    $this->get('/feed-cache-test')->assertOk()->assertSeeText('Original Feed');
    $this->travel(13)->hours();
    $this->get('/feed-cache-test')->assertOk()->assertSeeText('Updated Feed');
    Http::assertSentCount(2);
});

test('a failed deferred refresh preserves the stale feed for a subsequent retry', function () {
    Http::fakeSequence('example.com/feed.xml')
        ->push(widgetFeedXml('Original Feed'))
        ->pushStatus(503)
        ->push(widgetFeedXml('Updated Feed'));

    $this->get('/feed-cache-test')->assertOk()->assertSeeText('Original Feed');
    $this->travel(7)->hours();
    $this->get('/feed-cache-test')->assertOk()->assertSeeText('Original Feed');
    Http::assertSentCount(2);

    $this->get('/feed-cache-test')->assertOk()->assertSeeText('Original Feed');
    Http::assertSentCount(3);
    $this->get('/feed-cache-test')->assertOk()->assertSeeText('Updated Feed');
    Http::assertSentCount(3);
});

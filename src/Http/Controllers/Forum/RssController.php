<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Collection;
use Laminas\Feed\Writer\Feed;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

/**
 * Controller for RSS feeds.
 */
class RssController extends Controller
{
    public function posts()
    {
        $posts = Post::whereRelation('channel', 'ignore', false)
            ->latest()
            ->take(20)
            ->get();

        $feed = new Feed();
        $feed->setTitle(config('waterhole.forum.name'));
        $feed->setLink(route('waterhole.home'));

        return $this->postsFeed($feed, $posts);
    }

    public function channel(Channel $channel)
    {
        $posts = Post::whereBelongsTo($channel)
            ->latest()
            ->take(20)
            ->get();

        $feed = new Feed();
        $feed->setTitle($channel->name . ' - ' . config('waterhole.forum.name'));
        $feed->setLink($channel->url);

        return $this->postsFeed($feed, $posts);
    }

    private function postsFeed(Feed $feed, Collection $posts)
    {
        $posts->load('user');

        $feed->setDescription($feed->getTitle());
        $feed->setFeedLink(url()->current(), 'rss');
        $feed->setDateModified(now());

        foreach ($posts as $post) {
            $entry = $feed->createEntry();
            $entry->setId((string) $post->id);
            $entry->setTitle($post->title);
            $entry->setLink($post->url);
            $entry->setCommentLink($post->url . '#comments');
            // $entry->setCommentFeedLink([
            //     'uri' => route('waterhole.rss.post', compact('post')),
            //     'type' => 'rss',
            // ]);
            if ($post->user) {
                $entry->addAuthor([
                    'name' => $post->user->name,
                    'uri' => $post->user->url,
                ]);
            }
            $entry->setDateCreated($post->created_at);
            $entry->setDateModified($post->edited_at);
            if ($content = (string) $post->body_html) {
                $entry->setContent($content);
            }
            $feed->addEntry($entry);
        }

        // We use RSS over ATOM simply because Laminas Feed has an annoying
        // bug (or feature?) where it encodes entry content as XHTML, rather
        // than wrapping the HTML in CDATA. However, in the absence of the PHP
        // tidy extension, self-closing tags like <br> and <img> result in
        // invalid XML output. https://github.com/laminas/laminas-feed/issues/7
        return response($feed->export('rss'), 200, ['Content-Type' => 'application/rss+xml']);
    }
}

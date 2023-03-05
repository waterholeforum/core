<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Collection;
use Laminas\Feed\Writer\Feed;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

/**
 * Controller for Atom feeds.
 */
class AtomController extends Controller
{
    public function posts()
    {
        $posts = Post::whereRelation('channel', 'sandbox', false)
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
        $feed->setFeedLink(url()->current(), 'atom');
        $feed->setDateModified(now());

        foreach ($posts as $post) {
            $entry = $feed->createEntry();
            $entry->setTitle($post->title);
            $entry->setLink($post->url);
            $entry->setCommentLink($post->url . '#comments');
            // $entry->setCommentFeedLink([
            //     'uri' => route('waterhole.atom.post', compact('post')),
            //     'type' => 'atom',
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

        return response($feed->export('atom'), 200, ['Content-Type' => 'application/atom+xml']);
    }
}

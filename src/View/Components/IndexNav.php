<?php

namespace Waterhole\View\Components;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;

class IndexNav extends Component
{
    public Collection $nav;

    public function __construct()
    {
        $structure = Structure::where('is_listed', true)
            ->with([
                'content' => function (MorphTo $morphTo) {
                    if (Auth::check()) {
                        $morphTo->constrain([
                            Channel::class => function ($query) {
                                $query->withCount('unreadPosts');
                                $query->withNewPostsCount();
                            },
                        ]);
                    }
                },
            ])
            ->orderBy('position')
            ->get()
            ->filter(fn(Structure $node) => $node->content);

        $this->nav = collect([
            new NavLink(
                label: __('waterhole::forum.home'),
                icon: 'tabler-home',
                route: 'waterhole.home',
            ),
            ...$structure
                ->map(function (Structure $node) {
                    if ($node->content instanceof StructureHeading) {
                        return new NavHeading($node->content->name ?: '');
                    } elseif ($node->content instanceof Channel) {
                        return new NavLink(
                            label: $node->content->name,
                            icon: $node->content->icon,
                            badge: $node->content->new_posts_count +
                            $node->content->unread_posts_count ?:
                            null,
                            href: $node->content->url,
                        );
                    } elseif ($node->content instanceof StructureLink) {
                        return (new NavLink(
                            label: $node->content->name,
                            icon: $node->content->icon,
                            href: $node->content->href,
                        ))->withAttributes(
                            preg_match('~https?://~i', $node->content->href)
                                ? ['target' => '_blank']
                                : [],
                        );
                    } elseif ($node->content instanceof Page) {
                        return new NavLink(
                            label: $node->content->name,
                            icon: $node->content->icon,
                            href: $node->content->url,
                        );
                    }

                    return null;
                })
                ->filter(),
        ]);

        // Filter out headings with no items after them
        $this->nav = $this->nav->filter(function ($item, $i) {
            if ($item instanceof NavHeading) {
                return isset($this->nav[$i + 1]) && !($this->nav[$i + 1] instanceof NavHeading);
            }

            return true;
        });

        $this->nav->push(new IndexFooter());
    }

    public function render()
    {
        return $this->view('waterhole::components.index-nav');
    }
}

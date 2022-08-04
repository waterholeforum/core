<?php

namespace Waterhole\Views\Components;

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

    public $current;

    public function __construct()
    {
        $structure = Structure::where('is_listed', true)->with(['content' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Channel::class => ['permissions.recipient'],
            ]);

            if (Auth::check()) {
                $morphTo->morphWithCount([
                    Channel::class => ['newPosts', 'unreadPosts'],
                ]);
            }
        }])->orderBy('position')->get()->filter(fn (Structure $node) => $node->content);

        $this->nav = collect([
            [
                'route' => 'waterhole.home',
                'icon' => 'heroicon-o-home',
                'label' => __('waterhole::forum.home'),
            ],
            ...$structure->map(function (Structure $node) {
                if ($node->content instanceof StructureHeading) {
                    return [
                        'heading' => $node->content->name,
                    ];
                } elseif ($node->content instanceof Channel) {
                    return [
                        'url' => $node->content->url,
                        'icon' => $node->content->icon,
                        'label' => $node->content->name,
                        'badge' => ($node->content->new_posts_count + $node->content->unread_posts_count) ?: null,
                    ];
                } elseif ($node->content instanceof StructureLink) {
                    return [
                        'url' => $node->content->href,
                        'icon' => $node->content->icon,
                        'label' => $node->content->name,
                    ];
                } elseif ($node->content instanceof Page) {
                    return [
                        'url' => $node->content->url,
                        'icon' => $node->content->icon,
                        'label' => $node->content->name,
                    ];
                }

                return null;
            })->filter(),
        ]);

        $this->nav = $this->nav->filter(function (array $item, $i) {
            if (isset($item['heading'])) {
                return isset($this->nav[$i + 1]) && empty($this->nav[$i + 1]['heading']);
            }

            return true;
        });

        $this->current = $this->nav->first(function ($item) {
            if (isset($item['route'])) {
                return request()->routeIs($item['route'].'*');
            } elseif (isset($item['url'])) {
                return request()->fullUrlIs($item['url'].'*');
            }

            return false;
        });
    }

    public function render()
    {
        return view('waterhole::components.index-nav');
    }
}

<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostTagsSummary extends Component
{
    public Collection $tags;
    public int $limit = 3;

    public function __construct(public Post $post)
    {
        $this->tags = $post->tags->sortBy('taxonomy_id');
    }

    public function shouldRender(): bool
    {
        return $this->tags->isNotEmpty();
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="post-tags-summary row gap-xxs">
                @foreach ($tags->take($limit) as $tag)
                    <a
                        href="{{ route('waterhole.channels.show', ['channel' => $post->channel]) . '?'. Arr::query(['tags' => [$tag->taxonomy_id => $tag->id]]) }}"
                        class="tag"
                        data-tag-id="{{ $tag->id }}"
                    >{{ Waterhole\emojify($tag->name) }}</a>
                @endforeach
                @if ($tags->count() > $limit)
                    <span class="cursor-default">
                        +{{ $tags->count() - $limit }}
                        <ui-tooltip>
                            @foreach ($tags->slice($limit) as $tag)
                                <span data-tag-id="{{ $tag->id }}">
                                    {{ Waterhole\emojify($tag->name) }}
                                </span>
                            @endforeach
                        </ui-tooltip>
                    </span>
                @endif
            </div>
        blade;
    }
}

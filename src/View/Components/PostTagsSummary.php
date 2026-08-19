<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostTagsSummary extends Component
{
    public Collection $visibleTags;
    public Collection $hiddenTags;

    public function __construct(
        public Post $post,
    ) {
        $post->loadMissing('tags');

        $this->visibleTags = $post->tags->groupBy('taxonomy_id')->map->first()->take(3)->values();
        $this->hiddenTags = $post->tags->diff($this->visibleTags)->values();
    }

    public function shouldRender(): bool
    {
        return $this->visibleTags->isNotEmpty();
    }

    public function render()
    {
        return $this->view('waterhole::components.post-tags-summary');
    }
}

<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Feed\PostFeed;
use Waterhole\Models\Channel;
use Waterhole\Models\Taxonomy;

class TagsFilter extends Component
{
    public Collection $taxonomies;
    public int $selectedCount;
    public string $label;
    public array $preservedQuery;

    public function __construct(
        public PostFeed $feed,
        public ?Channel $channel,
    ) {
        $this->preservedQuery = array_filter(
            [
                'filter' => request('filter'),
                'period' => request('period'),
            ],
            fn($value) => $value !== null && $value !== '',
        );

        $this->taxonomies = $channel
            ?->taxonomies
            ->load('tags')
            ->filter(fn(Taxonomy $taxonomy) => $taxonomy->tags->isNotEmpty()) ?? collect();

        $selections = request('tags', []);
        $selectedIds = collect(is_array($selections) ? $selections : [])->flatten()->unique();
        $this->selectedCount = $selectedIds->count();

        $selectedTag = $this->selectedCount === 1
            ? $this->taxonomies->flatMap(fn(Taxonomy $taxonomy) => $taxonomy->tags)->firstWhere(
                'id',
                $selectedIds->first(),
            )
            : null;
        $this->label =
            $selectedTag?->name
            ?? (
                $this->selectedCount === 0 && $this->taxonomies->count() === 1
                    ? __($this->taxonomies->first()->name)
                    : __('waterhole::forum.tags-filter-button')
            );
    }

    public function shouldRender(): bool
    {
        return $this->taxonomies->isNotEmpty();
    }

    public function render()
    {
        return $this->view('waterhole::components.tags-filter');
    }

    public function clearHref(): string
    {
        return (
            url()->current()
            . ($this->preservedQuery ? '?' . Arr::query($this->preservedQuery) : '')
        );
    }
}

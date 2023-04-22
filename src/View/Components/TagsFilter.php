<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Feed\PostFeed;
use Waterhole\Models\Channel;
use Waterhole\Models\Taxonomy;

class TagsFilter extends Component
{
    public int $value;

    public function __construct(public PostFeed $feed, public ?Channel $channel)
    {
        $this->value = (int) request('tag_id');
    }

    public function shouldRender(): bool
    {
        return (bool) $this->channel?->taxonomies->isNotEmpty();
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="row gap-xxs wrap">
                @foreach ($channel->taxonomies->load('tags') as $taxonomy)
                    @if ($id = request("tags.$taxonomy->id"))
                        <a href="{{ $href($taxonomy, null) }}" class="tab is-active">
                            {{ $taxonomy->tags->find($id)?->name }}
                            @icon('tabler-x')
                        </a>
                    @else
                        <x-waterhole::selector
                            button-class="tab"
                            placement="bottom-end"
                            :options="$taxonomy->tags->modelKeys()"
                            :placeholder="__($taxonomy->name)"
                            :label="fn($id) => $taxonomy->tags->find($id)->name ?? ''"
                            :href="fn($id) => $href($taxonomy, $id)"
                        />
                    @endif
                @endforeach
            </div>
        blade;
    }

    public function href(Taxonomy $taxonomy, $id)
    {
        return request()->fullUrlWithQuery([
            'tags' => array_replace(request('tags', []), [$taxonomy->id => $id]),
        ]);
    }
}

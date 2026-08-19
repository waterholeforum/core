<ui-popup class="post-feed__tags-filter" placement="bottom-end">
    <button type="button" @class(['tab', 'is-active' => $selectedCount])>
        @icon('tabler-tag')
        <span>{{ $label }}</span>
        @if ($selectedCount > 1)
            <span class="badge">{{ $selectedCount }}</span>
        @endif
    </button>

    <form
        action="{{ url()->current() }}"
        method="GET"
        class="menu p-0"
        data-controller="watch-scroll"
        hidden
    >
        @foreach ($preservedQuery as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
        @endforeach

        <div class="stack gap-sm p-xs">
            @foreach ($taxonomies as $taxonomy)
                <div
                    role="group"
                    aria-labelledby="tag-filter-taxonomy-{{ $taxonomy->id }}"
                >
                    <div
                        id="tag-filter-taxonomy-{{ $taxonomy->id }}"
                        class="menu-heading"
                    >
                        {{ __($taxonomy->name) }}
                    </div>

                    @foreach ($taxonomy->tags as $tag)
                        <label class="menu-item">
                            <input
                                type="checkbox"
                                name="tags[{{ $taxonomy->id }}][]"
                                value="{{ $tag->id }}"
                                @checked(in_array($tag->id, Arr::wrap(request("tags.$taxonomy->id"))))
                            />
                            <span>{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="sticky-bottom row gap-xs justify-end p-xs">
            <button type="submit" class="btn bg-accent">
                {{ __('waterhole::forum.tags-filter-apply-button') }}
            </button>
            <a href="{{ $clearHref() }}" class="btn">
                {{ __('waterhole::forum.tags-filter-clear-button') }}
            </a>
        </div>
    </form>
</ui-popup>

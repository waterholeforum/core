<div class="post-tags-summary row wrap justify-end gap-xxs color-muted">
    @foreach ($visibleTags as $tag)
        <a
            href="{{ route('waterhole.channels.show', ['channel' => $post->channel]) . '?' . Arr::query(['tags' => [$tag->taxonomy_id => [$tag->id]]]) }}"
            class="tag"
            data-tag-id="{{ $tag->id }}"
        >
            {{ Waterhole\emojify($tag->name) }}
        </a>
    @endforeach

    @if ($hiddenTags->isNotEmpty())
        <span class="cursor-default nowrap">
            +{{ $hiddenTags->count() }}
            <ui-tooltip>
                @foreach ($hiddenTags as $tag)
                    <span class="tag" data-tag-id="{{ $tag->id }}">
                        {{ Waterhole\emojify($tag->name) }}
                    </span>
                @endforeach
            </ui-tooltip>
        </span>
    @endif
</div>

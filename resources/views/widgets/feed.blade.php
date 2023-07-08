<div class="card card__body stack gap-lg full-height">
    <h3 class="h4">
        <a
            href="{{ $feed->getLink() }}"
            class="with-icon color-inherit"
            target="_blank"
            rel="noopener"
        >
            @icon('tabler-rss')
            {{ $title ?: $feed->getTitle() }}
        </a>
    </h3>

    @foreach ($feed as $item)
        @continue($loop->index >= $limit)

        <article class="stack gap-xxs overlay-container">
            <a
                href="{{ $item->getLink() }}"
                class="h6 color-accent block has-overlay"
                target="_blank"
                rel="noopener"
            >
                {{ $item->getTitle() }}
            </a>

            <p class="color-muted text-xxs">
                <x-waterhole::relative-time :datetime="$item->getDateCreated()" />
                —
                {{ Str::limit(htmlspecialchars_decode(strip_tags($item->getDescription() ?: $item->getContent()), 200)) }}
            </p>
        </article>
    @endforeach
</div>

<article
    {{
        $attributes
            ->class('card card__body post-card stack gap-md')
            ->merge(resolve(\Waterhole\Extend\Ui\PostAttributes::class)->build($post))
    }}
    data-controller="post"
>
    <header class="post-card__header row justify-between align-start">
        <div class="stack gap-lg">
            @empty($config['hide_author'])
                <x-waterhole::post-attribution :$post />
            @endempty

            <h3 class="post-card__title">
                <a
                    href="{{ $post->isUnread() ? $post->unread_url : $post->url }}"
                    data-action="post#appearAsRead"
                    class="post-title-link"
                >
                    {{ Waterhole\emojify($post->title) }}
                </a>
            </h3>
        </div>

        <x-waterhole::action-menu :for="$post" placement="bottom-end" />
    </header>

    <div
        class="post-card__content content content--compact text-sm truncated"
        data-controller="truncated"
    >
        {{ $post->body_html }}

        <button
            type="button"
            class="truncated__expander link weight-bold"
            hidden
            data-truncated-target="expander"
            data-action="truncated#expand"
        >
            {{ __('waterhole::system.show-more-button') }}
        </button>
    </div>

    <div class="row gap-xs wrap">
        @components(\Waterhole\Extend\Ui\PostFooter::class, compact('post'))
    </div>
</article>

<article
    {{
        $attributes
            ->class('post-list-item card__row row align-start gap-md')
            ->merge(Waterhole\Extend\PostAttributes::build($post))
    }}
    data-controller="post"
>
    <x-waterhole::user-link :user="$post->user" class="post-list-item__avatar">
        <x-waterhole::avatar :user="$post->user" />
        <ui-tooltip>
            {{ Waterhole\username($post->user) }}
            {{ __('waterhole::forum.post-activity-posted') }}
            <x-waterhole::relative-time :datetime="$post->created_at" />
        </ui-tooltip>
    </x-waterhole::user-link>

    <div class="post-list-item__inner grow stack gap-sm">
        <div class="post-list-item__upper row gap-sm align-start">
            <div class="post-list-item__main grow stack gap-xxs">
                <h3 class="post-list-item__title h4 weight-medium">
                    <a
                        href="{{ $post->isUnread() ? $post->unread_url : $post->url }}"
                        data-action="post#appearAsRead"
                        class="post-title-link"
                    >
                        {{ $title }}
                    </a>
                </h3>

                <div class="post-list-item__info row wrap gap-y-xxs gap-x-sm text-xs color-muted">
                    @components(Waterhole\Extend\PostInfo::build(), compact('post'))
                </div>
            </div>

            <div class="post-list-item__end row wrap justify-end gap-xs align-center">
                @components(Waterhole\Extend\PostListItem::build(), compact('post', 'config'))
            </div>
        </div>

        @if ($excerpt)
            <div class="post-list-item__excerpt content text-xs measure">
                <p>{{ $excerpt }}</p>
            </div>
        @endif
    </div>

    <div class="post-list-item__controls hide-sm">
        <x-waterhole::action-menu :for="$post" placement="bottom-end" />
    </div>
</article>

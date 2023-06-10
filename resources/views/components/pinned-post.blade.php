<div
    {{ $attributes
        ->merge(['data-controller' => $attributes->prepends('post')])
        ->class('card card__body stack gap-sm')
        ->merge(Waterhole\Extend\PostAttributes::build($post)) }}
>
    <div class="row gap-x-sm">
        <x-waterhole::post-unread :post="$post"/>
        <x-waterhole::channel-label :channel="$post->channel" link class="text-xs"/>
        <x-waterhole::action-menu :for="$post" placement="bottom-end" class="push-end -m-xs"/>
    </div>

    <div class="stack gap-xxs overlay-container">
        <h3 class="h4 weight-medium">
            <a
                href="{{ $post->isUnread() ? $post->unread_url : $post->url }}"
                data-action="post#appearAsRead"
                class="post-title-link has-overlay"
            >{{ Waterhole\emojify($post->title) }}</a>
        </h3>

        <div class="content text-xs measure color-muted">
            <p>{{ Waterhole\emojify(Str::limit($post->body_text, 100)) }}</p>
        </div>
    </div>
</div>

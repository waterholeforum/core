<div {{ $attributes->class('post-summary') }}>
    <x-waterhole::user-link :user="$post->user" class="post-summary__avatar">
        <x-waterhole::avatar :user="$post->user"/>
        <ui-tooltip>
            {{ Waterhole\username($post->user) }}
            {{ __('waterhole::forum.post-activity-posted') }}
            <x-waterhole::time-ago :datetime="$post->created_at"/>
        </ui-tooltip>
    </x-waterhole::user-link>

    <div class="post-summary__content">
        <h3 class="post-summary__title h4">
            <a
                href="{{ $post->isUnread() ? $post->unread_url : $post->url }}"
                data-action="post#appearAsRead"
            >{{ Waterhole\emojify($post->title) }}</a>
        </h3>
        <div class="post-summary__info">
            @components(Waterhole\Extend\PostInfo::build(), compact('post'))
        </div>
    </div>
</div>

<div {{ $attributes->class('post-summary') }}>
    <x-waterhole::avatar :user="$post->user" class="post-summary__avatar"/>
    <div class="post-summary__content">
        <h3 class="post-summary__title">
            <a
                href="{{ $post->isUnread() ? $post->unread_url : $post->url }}"
                data-action="post#appearAsRead"
            >{{ $post->title }}</a>
        </h3>
        <div class="post-summary__info">
            @components(Waterhole\Extend\PostInfo::getComponents(), compact('post'))
        </div>
    </div>
</div>

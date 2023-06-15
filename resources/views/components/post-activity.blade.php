@if ($post->lastComment)
    <span>
        <x-waterhole::user-link
            :user="$post->lastComment->user"
            class="color-inherit inline-block"
        >
            {{ Waterhole\username($post->lastComment->user) }}
        </x-waterhole::user-link>
        {{ __('waterhole::forum.post-activity-replied') }}
        <a href="{{ $post->urlAtIndex($post->comment_count) }}#bottom" class="color-inherit">
            <x-waterhole::relative-time :datetime="$post->last_activity_at" />
        </a>
    </span>
@elseif ($post->user)
    <span>
        <x-waterhole::user-link :user="$post->user" class="color-inherit inline-block">
            {{ Waterhole\username($post->user) }}
        </x-waterhole::user-link>
        {{ __('waterhole::forum.post-activity-posted') }}
        <a href="{{ $post->url }}" class="color-inherit">
            <x-waterhole::relative-time :datetime="$post->created_at" />
        </a>
    </span>
@endif

@if ($post->lastComment)
    <span>
        <x-waterhole::user-link :user="$post->lastComment->user" class="color-inherit">
            {{ Waterhole\username($post->lastComment->user) }}
        </x-waterhole::user-link>
        {{ __('waterhole::forum.post-activity-replied') }}
        <a href="{{ $post->urlAtIndex($post->comment_count) }}#bottom" class="color-inherit">
            <x-waterhole::time-ago :datetime="$post->last_activity_at" format="micro"/>
        </a>
    </span>
@elseif ($post->user)
    <span>
        <x-waterhole::user-link :user="$post->user" class="color-inherit">
            {{ Waterhole\username($post->user) }}
        </x-waterhole::user-link>
        {{ __('waterhole::forum.post-activity-posted') }}
        <a href="{{ $post->url }}" class="color-inherit">
            <x-waterhole::time-ago :datetime="$post->created_at" format="micro"/>
        </a>
    </span>
@endif

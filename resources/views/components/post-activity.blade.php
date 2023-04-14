@if ($post->lastComment)
    <span>
        <x-waterhole::user-link :user="$post->lastComment->user" class="color-inherit">
            {{ Waterhole\username($post->lastComment->user) }}
        </x-waterhole::user-link>
        {{ __('waterhole::forum.post-activity-replied') }}
        <x-waterhole::time-ago :datetime="$post->last_activity_at" format="micro"/>
    </span>
@elseif ($post->user)
    <span>
        <x-waterhole::user-link :user="$post->user" class="color-inherit">
            {{ Waterhole\username($post->user) }}
        </x-waterhole::user-link>
        {{ __('waterhole::forum.post-activity-posted') }}
        <x-waterhole::time-ago :datetime="$post->created_at" format="micro"/>
    </span>
@endif

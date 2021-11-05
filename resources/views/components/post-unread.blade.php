<x-waterhole::action-form
    :for="$post"
    :action="Waterhole\Actions\MarkAsRead::class"
    class="post-summary__unread"
>
    <button type="submit" class="badge clickable @if ($isNotifiable) badge--unread @endif">
        @if ($isNotifiable)
            <x-waterhole::icon icon="heroicon-s-bell"/>
        @endif
        @if ($post->isNew())
            <span>{{ __('waterhole::forum.post-new-post') }}</span>
        @else
            <span>{{ $post->unread_comments_count }}</span>
            <ui-tooltip placement="bottom">{{ $post->unread_comments_count }} unread</ui-tooltip>
        @endif
    </button>
</x-waterhole::action-form>

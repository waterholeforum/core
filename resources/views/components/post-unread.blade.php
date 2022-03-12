<x-waterhole::action-form
    :for="$post"
    :action="Waterhole\Actions\MarkAsRead::class"
    class="post-summary__unread"
>
    <button type="submit" class="badge clickable @if ($isNotifiable) bg-activity @endif">
        @if ($isNotifiable)
            <x-waterhole::icon icon="heroicon-s-bell"/>
        @endif
        @if ($post->isNew())
            <span>{{ __('waterhole::forum.post-new-post') }}</span>
            <ui-tooltip placement="bottom">
                New post<br>
                <small>Click to mark as read</small>
            </ui-tooltip>
        @else
            <span>{{ $post->unread_comments_count }}</span>
            <ui-tooltip placement="bottom">
                {{ $post->unread_comments_count }} unread comments<br>
                <small>Click to mark as read</small>
            </ui-tooltip>
        @endif
    </button>
</x-waterhole::action-form>

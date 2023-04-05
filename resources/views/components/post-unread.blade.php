<x-waterhole::action-form
    :for="$post"
    :action="Waterhole\Actions\MarkAsRead::class"
    class="post-summary__unread"
>
    <button type="submit" class="badge clickable @if ($isNotifiable) bg-activity @endif">
        @if ($isNotifiable)
            @icon('tabler-bell')
        @endif
        @if ($post->isNew())
            <span>{{ __('waterhole::forum.post-new-badge') }}</span>
            <ui-tooltip placement="bottom">
                {{ __('waterhole::forum.post-new-badge-tooltip') }}<br>
                <small>{{ __('waterhole::forum.mark-as-read-instruction') }}</small>
            </ui-tooltip>
        @else
            <span>{{ $post->unread_comments_count }}</span>
            <ui-tooltip placement="bottom">
                {{ __('waterhole::forum.post-unread-comments-badge-tooltip', ['count' => $post->unread_comments_count]) }}<br>
                <small>{{ __('waterhole::forum.mark-as-read-instruction') }}</small>
            </ui-tooltip>
        @endif
    </button>
</x-waterhole::action-form>

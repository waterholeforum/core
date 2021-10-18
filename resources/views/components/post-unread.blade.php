@if ($post->is_unread)
    <x-waterhole::action-form
        :for="$post"
        :action="Waterhole\Actions\MarkAsRead::class"
    >
        <button type="submit" class="post-summary__unread badge clickable" title="Click to mark as read">
            @if ($post->userState->last_read_at)
                {{ $post->unread_comments_count }}
            @else
                {{ __('waterhole::forum.post-new-post') }}
            @endif
        </button>
    </x-waterhole::action-form>
@endif

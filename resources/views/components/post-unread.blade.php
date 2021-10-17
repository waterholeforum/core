@props(['post'])

@if ($post->isUnread())
    <x-waterhole::action-form
        :for="$post"
        :action="Waterhole\Actions\MarkAsRead::class"
    >
        <button type="submit" class="post-summary__unread badge clickable">
            @if ($post->userState->last_read_at && ($count = $post->comment_count - $post->userState->last_read_index))
                {{ $count }}
            @else
                {{ __('waterhole::forum.post-new-post') }}
            @endif
        </button>
    </x-waterhole::action-form>
@endif

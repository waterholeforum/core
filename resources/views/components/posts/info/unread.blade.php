@props(['post'])

@if ($post->userState && (
    ! $post->userState->last_read_at
    || $post->userState->last_read_at < $post->last_comment_at
))
    <a href="{{ $post->url }}" class="post-summary__unread">
        @if ($count = $post->comment_count - $post->userState->last_read_index)
            {{ __('waterhole::forum.post-new-comments', compact('count')) }}
        @else
            {{ __('waterhole::forum.post-new-post') }}
        @endif
    </a>
@endif

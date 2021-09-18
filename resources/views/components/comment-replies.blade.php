@props(['comment'])

{{--<x-waterhole::action-button--}}
{{--    :for="$comment"--}}
{{--    :action="Waterhole\Actions\Reply::class"--}}
{{--    class="btn btn--transparent btn--small"--}}
{{--/>--}}

@if ($comment->reply_count)
    <a href="{{ $comment->url }}#comment-{{ $comment->id }}" class="comment__view-replies btn btn--link btn--small">
        {{ __('waterhole::forum.comment-reply-count', ['count' => $comment->reply_count]) }}
    </a>
@endif

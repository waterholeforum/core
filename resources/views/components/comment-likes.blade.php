@props(['comment'])

<x-waterhole::action-button
    :for="$comment"
    :action="Waterhole\Actions\Like::class"
    class="btn btn--transparent btn--small"
    :return="request()->fullUrl().'#comment-'.$comment->id"
/>

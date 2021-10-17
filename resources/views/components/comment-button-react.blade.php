@props(['comment'])

<x-waterhole::action-button
    :for="$comment"
    :action="Waterhole\Actions\React::class"
    class="btn btn--small btn--transparent"
    :return="request()->fullUrl().'#comment-'.$comment->id"
/>

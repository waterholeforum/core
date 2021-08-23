@props(['comment'])

<x-waterhole::actions.button
    :for="$comment"
    :action="Waterhole\Actions\Reply::class"
    class="btn btn--transparent btn--small"
/>

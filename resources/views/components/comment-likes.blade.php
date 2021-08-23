@props(['comment'])

<x-waterhole::actions.button
    :for="$comment"
    :action="Waterhole\Actions\Like::class"
    class="btn btn--transparent btn--small"
/>

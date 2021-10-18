@props(['comment'])

{{--<x-waterhole::action-button--}}
{{--    :for="$comment"--}}
{{--    :action="Waterhole\Actions\React::class"--}}
{{--    class="btn btn--small btn--transparent"--}}
{{--    :return="request()->fullUrl().'#comment-'.$comment->id"--}}
{{--/>--}}

<button class="btn btn--small btn--transparent btn--icon comment__control">
    <x-waterhole::icon icon="heroicon-o-emoji-happy"/>
</button>

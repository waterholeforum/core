<x-waterhole::action-button
    :for="$model"
    :action="Waterhole\Actions\Like::class"
    :return="$model->url"
    {{ $attributes->class('btn btn--small btn--transparent') }}
/>

{{--<button class="btn btn--small btn--transparent btn--icon comment__control">--}}
{{--    <x-waterhole::icon icon="heroicon-o-emoji-happy"/>--}}
{{--</button>--}}

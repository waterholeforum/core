<x-waterhole::action-button
    :for="$model"
    :action="Waterhole\Actions\Like::class"
    :return="$model->url"
    {{ $attributes->class('btn btn--sm btn--transparent') }}
/>

{{--<button class="btn btn--sm btn--transparent btn--icon control">--}}
{{--    <x-waterhole::icon icon="tabler-emoji-happy"/>--}}
{{--</button>--}}

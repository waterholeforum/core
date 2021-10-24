@if ($model->score)
    <span {{ $attributes->class([
        'btn btn--small btn--transparent',
    ]) }}>
        <x-waterhole::icon icon="emoji:👍"/>
        <span>{{ $model->score }}</span>
    </span>
@endif

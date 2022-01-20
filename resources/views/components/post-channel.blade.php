{{--<div class="row gap-xs">--}}
{{--    <a href="{{ route('waterhole.home') }}" class="channel-label color-muted">--}}
{{--        <x-waterhole::icon icon="heroicon-o-home"/>--}}
{{--        {{ config('waterhole.forum.title') }}--}}
{{--    </a>--}}

{{--    <span class="color-muted">›</span>--}}

    <span>
        <x-waterhole::channel-label :channel="$post->channel" link/>
    </span>
{{--</div>--}}

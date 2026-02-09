@php
    $tag = 'span';
    $attributes = $attributes->class('channel-label')->merge(['data-channel' => $channel->slug]);

    if ($link) {
        $tag = 'a';
        $attributes = $attributes->merge(['href' => $channel->url]);
    }
@endphp

@if ($channel)
    <{{ $tag }} {{ $attributes }}>
        @icon($channel->icon)
        <span>{{ $channel->name }}</span>
    </{{ $tag }}>
@endif

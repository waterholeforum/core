@props(['channel' => null, 'link' => false])

@php
    $tag = 'span';
    $attributes = $attributes->class('channel-name');

    if ($link) {
        $tag = 'a';
        $attributes = $attributes->merge(['href' => $channel->url]);
    }
@endphp

@if ($channel)
    <{{ $tag }} {{ $attributes }}>
        <x-waterhole::icon :icon="$channel->icon"/>
        <span>{{ $channel->name }}</span>
    </{{ $tag }}>
@endif

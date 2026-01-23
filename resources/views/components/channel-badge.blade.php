@php
    $tag = 'span';
    $attributes = $attributes->class('badge channel-badge');

    if ($link) {
        $tag = 'a';
        $attributes = $attributes->merge(['href' => $channel->url]);
    }
@endphp

@if ($channel)
    <{{ $tag }} {{ $attributes }}
        @if ($channel->color)
            style="
                --channel-color: #{{ $channel->color }};
                --channel-contrast: {{ Waterhole\get_contrast_color($channel->color) }};
            "
        @endif
    >
        @icon($channel->icon)
        <span>{{ $channel->name }}</span>
    </{{ $tag }}>
@endif

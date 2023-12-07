@php
    $enabled = $response === true || $response->allowed();
    $tag = $enabled ? 'a' : 'span';
    if ($enabled) {
        $attributes = $attributes->merge(['href' => route('waterhole.posts.create', ['channel_id' => $channel?->id])]);
    }
@endphp

<{{ $tag }}
    {{ $attributes->class(['btn index-create-post', $enabled ? 'bg-accent' : 'is-disabled']) }}
>
    {{ __($channel->translations[($key = 'waterhole::forum.create-post-button')] ?? $key) }}

    @unless ($enabled)
        <ui-tooltip>
            {{ $response->message() ?: __('waterhole::system.forbidden-message') }}
        </ui-tooltip>
    @endunless
</{{ $tag }}>

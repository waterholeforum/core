@php
    $enabled = $response === true || $response->allowed();
    $tag = $enabled ? 'a' : 'span';
    if ($enabled) {
        $attributes = $attributes->merge(['href' => route('waterhole.posts.create', ['channel_id' => $channel?->id])]);
    }
    $defaultLabel = __('waterhole::forum.create-post-button');
@endphp

<{{ $tag }} {{ $attributes->class(['btn text-md index-create-post', $enabled ? 'bg-accent' : 'is-disabled']) }}>
    {{ $channel
        ? Waterhole\trans_optional("waterhole.channel-$channel->slug-create-post-button", $defaultLabel)
        : $defaultLabel }}

    @unless ($enabled)
        <ui-tooltip>
            {{ $response->message() ?: __('waterhole::system.forbidden-message') }}
        </ui-tooltip>
    @endunless
</{{ $tag }}>

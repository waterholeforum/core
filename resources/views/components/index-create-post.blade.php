@php
    $enabled = $response === true || $response->allowed();
    $showDraft = $enabled && $hasDraft;
    $tag = $enabled ? 'a' : 'span';
    if ($enabled) {
        $attributes = $attributes->merge(['href' => route('waterhole.posts.create', ['channel_id' => $channel?->id])]);
    }
@endphp

<{{ $tag }}
    {{ $attributes->class(['btn index-create-post', $showDraft ? 'bg-activity' : ($enabled ? 'bg-accent' : 'is-disabled')]) }}
>
    @if ($showDraft)
        @icon('tabler-pencil')
        {{ __('waterhole::forum.resume-draft-button') }}
    @else
        {{ __($channel->translations[($key = 'waterhole::forum.create-post-button')] ?? $key) }}
    @endif

    @unless ($enabled)
        <ui-tooltip>
            {{ $response->message() ?: __('waterhole::system.forbidden-message') }}
        </ui-tooltip>
    @endunless
</{{ $tag }}>

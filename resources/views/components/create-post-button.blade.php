@php
    $enabled = $enabled();
    $showDraft = $showDraft();
    $tag = $enabled ? 'a' : 'span';
@endphp

<{{ $tag }}
    {{ $attributes->class(['btn index-create-post', $showDraft ? 'bg-activity' : ($enabled ? 'bg-accent' : 'is-disabled')])->merge($enabled ? ['href' => $href()] : []) }}
>
    @if ($showDraft)
        @icon('tabler-pencil')
    @endif

    {{ $label() }}

    @unless ($enabled)
        <ui-tooltip>
            {{ $forbiddenMessage() }}
        </ui-tooltip>
    @endunless
</{{ $tag }}>

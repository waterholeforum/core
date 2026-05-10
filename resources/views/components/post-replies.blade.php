@blaze
@props(['post'])

@php
    $count = $post->comment_count;
    $labelKey = app()->getLocale() . ':' . $count;

    static $labels = [];
    $label = $labels[$labelKey] ??= [
        'count' => Waterhole\compact_number($count),
        'tooltip' => __('waterhole::forum.post-comments-link', ['count' => $count]),
    ];
@endphp

<a
    href="{{ $post->url }}#comments"
    class="btn btn--sm btn--outline @if (!$count) is-disabled @endif"
    data-action="post#appearAsRead"
>
    @icon('tabler-message-circle-2')
    <span>{{ $label['count'] }}</span>
    <ui-tooltip>
        {{ $label['tooltip'] }}
    </ui-tooltip>
</a>

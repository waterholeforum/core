<span
    {{ $attributes->class(["badge group-badge", "badge--hidden" => ! $group->is_public]) }}
    @if ($group->is_public && $group->color)
        style="
                --group-color: #{{ $group->color }};
                --group-color-constrast: {{ Waterhole\get_contrast_color($group->color) }}
            "
    @endif
>
    @if ($group->is_public && $group->icon)
        @icon($group->icon)
    @endif

    <span>{{ $group->name }}</span>
</span>

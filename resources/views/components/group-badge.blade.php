<span
    {{ $attributes->class(['badge group-badge', 'group-badge--hidden' => !$group->is_public]) }}
    @if ($group->is_public && $group->color) style="
        --group-color: #{{ $group->color }};
        --group-color-constrast: {{ Waterhole\get_contrast_color($group->color) }}
    " @endif
>
    @if ($group->is_public && $group->icon)
        <x-waterhole::icon :icon="$group->icon"/>
    @endif
    <span>{{ $group->name }}</span>
</span>

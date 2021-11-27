<span {{ $attributes->class('group-label badge') }} @if ($group->is_public && $group->color) style="--group-color: #{{ $group->color }}; --group-color-constrast: {{ get_contrast_color($group->color) }}" @endif>
    @if ($group->is_public && $group->icon) <x-waterhole::icon :icon="$group->icon"/> @endif
    <span>{{ $group->name }}</span>
</span>
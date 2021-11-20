<span {{ $attributes->class('group-label badge') }} @if ($group->color) style="--group-color: #{{ $group->color }}; --group-color-constrast: {{ get_contrast_color($group->color) }}" @endif>
    @if ($group->icon) <span><x-waterhole::icon :icon="$group->icon"/></span> @endif
    <span>{{ $group->name }}</span>
</span>

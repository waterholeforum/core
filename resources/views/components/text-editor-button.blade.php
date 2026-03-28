<button
    type="button"
    @if ($shortcut)
        data-shortcut-trigger="{{ $shortcut }}"
    @endif
    @if ($format)
        data-action="text-editor#format"
        data-text-editor-format-param="{{ is_array($format) ? json_encode($format) : $format }}"
    @endif
    {{ $attributes->class("btn btn--transparent btn--icon") }}
>
    @icon($icon)
    <ui-tooltip>
        {{ $label }}
        @if ($shortcut)
            <x-waterhole::shortcut-label :shortcut="$shortcut" />
        @elseif ($hint)
            <span class="shortcut-label">{{ $hint }}</span>
        @endif
    </ui-tooltip>
</button>

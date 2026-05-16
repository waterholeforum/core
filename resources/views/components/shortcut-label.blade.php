@blaze

@props(['shortcut' => null])

@php
    $shortcut = $shortcut instanceof Waterhole\Ui\KeyboardShortcut ? $shortcut->id : $shortcut;
@endphp

@if ($shortcut)
    <kbd
        {{
            $attributes->class(['shortcut-label js-only'])->merge([
                'data-shortcut-label' => $shortcut,
                'aria-hidden' => 'true',
            ])
        }}
    ></kbd>
@endif

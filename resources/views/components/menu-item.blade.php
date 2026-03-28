@php
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    {{
        $attributes->merge([
            'class' => 'menu-item',
            'href' => $href,
            'role' => $active !== null ? 'menuitemradio' : 'menuitem',
            'aria-checked' => $active && $tag === 'button' ? 'true' : null,
            'aria-current' => $active && $tag === 'a' ? 'page' : null,
            'data-shortcut-trigger' => $shortcut,
        ])
    }}
>
    @icon($icon)

    @empty($description)
        {{ $label }}
    @else
        <span>
            <span class="menu-item__title">{{ $label }}</span>
            <span class="menu-item__description">{{ $description }}</span>
        </span>
    @endempty

    @if ($shortcut)
        <x-waterhole::shortcut-label :shortcut="$shortcut" class="menu-item__shortcut" />
    @endif

    @if ($active)
        @icon('tabler-check', ['class' => 'menu-item__check'])
    @endif
</{{ $tag }}>

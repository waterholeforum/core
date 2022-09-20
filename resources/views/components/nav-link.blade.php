<a {{
    $attributes
        ->merge(['href' => $href ?: ($route ? route($route) : null)])
        ->class(['nav-link', 'is-active' => $isActive()])
}}>
    <x-waterhole::icon :icon="$icon"/>
    <span class="label">{{ $label }}</span>
    {{ $slot ?? null }}
    @isset ($badge)
        <span class="badge {{ $badgeClass }}">{{ $badge }}</span>
    @endisset
</a>

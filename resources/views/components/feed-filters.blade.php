<div
    data-controller="watch-scroll"
    {{ $attributes->class('tabs nowrap scrollable-x shrink') }}
>
    @components($promotedComponents->all())

    @if ($overflowComponents->isNotEmpty() || $systemComponents->isNotEmpty())
        <ui-popup placement="bottom-start">
            <button
                type="button"
                @class(['tab', 'is-active' => $activeOverflowComponent])
            >
                @if ($activeOverflowComponent)
                    @if ($activeOverflowComponent->icon)
                        @icon($activeOverflowComponent->icon)
                    @endif

                    <span>{{ $activeOverflowComponent->label }}</span>
                    @icon('tabler-selector', ['class' => 'icon--narrow'])
                @else
                    @icon('tabler-dots', ['aria-label' => __('waterhole::system.more-button')])
                @endif
            </button>

            <ui-menu class="menu" hidden>
                @foreach (collect([$overflowComponents, $systemComponents])->filter->isNotEmpty() as $group)
                    @unless ($loop->first)
                        <hr class="menu-divider" />
                    @endunless

                    @foreach ($group as $component)
                        <a
                            href="{{ $component->href }}"
                            class="menu-item"
                            role="menuitemradio"
                            aria-checked="{{ $component->isActive ? 'true' : 'false' }}"
                        >
                            @if ($component->icon)
                                @icon($component->icon)
                            @endif

                            <span>{{ $component->label }}</span>
                        </a>
                    @endforeach
                @endforeach
            </ui-menu>
        </ui-popup>
    @endif
</div>

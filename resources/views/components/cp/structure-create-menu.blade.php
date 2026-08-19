@php
    $parameters = $parent ? ['parent_id' => $parent->getKey()] : [];
@endphp

<ui-popup placement="bottom-end">
    @if ($slot->isEmpty())
        <button type="button" class="btn bg-accent">
            @icon('tabler-circle-plus')
            <span>{{ __('waterhole::system.create-button') }}</span>
            @icon('tabler-chevron-down')
        </button>
    @else
        {{ $slot }}
    @endif

    <ui-menu class="menu" hidden>
        <a
            href="{{ route('waterhole.cp.structure.channels.create', $parameters) }}"
            class="menu-item"
            role="menuitem"
        >
            @icon('tabler-message-circle-2')
            <span>{{ __('waterhole::cp.structure-channel-label') }}</span>
        </a>
        <a
            href="{{ route('waterhole.cp.structure.pages.create', $parameters) }}"
            class="menu-item"
            role="menuitem"
        >
            @icon('tabler-file-text')
            <span>{{ __('waterhole::cp.structure-page-label') }}</span>
        </a>
        <a
            href="{{ route('waterhole.cp.structure.links.create', $parameters) }}"
            class="menu-item"
            role="menuitem"
        >
            @icon('tabler-link')
            <span>{{ __('waterhole::cp.structure-link-label') }}</span>
        </a>
        <a
            href="{{ route('waterhole.cp.structure.headings.create', $parameters) }}"
            class="menu-item"
            role="menuitem"
        >
            @icon('tabler-hash')
            <span>{{ __('waterhole::cp.structure-heading-label') }}</span>
        </a>
    </ui-menu>
</ui-popup>

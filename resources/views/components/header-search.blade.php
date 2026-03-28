<a
    href="{{ route('waterhole.search') }}"
    class="btn btn--icon btn--transparent header-search__button hide-lg-up"
    data-shortcut-trigger="navigation.search"
>
    @icon('tabler-search')
    <ui-tooltip>
        {{ __('waterhole::forum.search-button') }}
        <x-waterhole::shortcut-label shortcut="navigation.search" />
    </ui-tooltip>
</a>

<x-waterhole::search-form class="header-search__form hide-md-down" />

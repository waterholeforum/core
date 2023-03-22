<a
    href="{{ route('waterhole.search') }}"
    class="btn btn--icon btn--transparent header-search__button hide-lg-up"
>
    <x-waterhole::icon icon="tabler-search"/>
    <ui-tooltip>{{ __('waterhole::forum.search-button') }}</ui-tooltip>
</a>

<x-waterhole::search-form class="header-search__form hide-md-down"/>

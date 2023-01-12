<a
    href="{{ route('waterhole.search') }}"
    class="btn btn--icon btn--transparent header-search__button hide-md-up"
>
    <x-waterhole::icon icon="tabler-search"/>
    <ui-tooltip>{{ __('waterhole::forum.search-button') }}</ui-tooltip>
</a>

<form
    action="{{ route('waterhole.search') }}"
    class="input-container header-search__form hide-sm-down"
>
    <x-waterhole::icon
        icon="tabler-search"
        class="no-pointer color-muted"
    />

    <input
        class="rounded-full"
        type="text"
        name="q"
        placeholder="{{ __('waterhole::forum.search-placeholder') }}"
        required
        data-hotkey="/"
    >

    <div class="hide-if-invalid">
        <button
            type="submit"
            class="btn btn--icon btn--transparent btn--sm color-accent"
            aria-label="{{ __('waterhole::forum.search-button') }}"
        >
            <x-waterhole::icon icon="tabler-arrow-right"/>
        </button>
    </div>
</form>

<a
    href="{{ route('waterhole.search') }}"
    class="btn btn--icon btn--transparent header-search__button"
>
    <x-waterhole::icon icon="heroicon-o-search"/>
    <ui-tooltip>{{ __('waterhole::forum.search-button') }}</ui-tooltip>
</a>

<form
    action="{{ route('waterhole.search') }}"
    class="input-container header-search__form"
    style="margin-right: var(--space-xs)"
>
    <x-waterhole::icon
        icon="heroicon-o-search"
        class="pointer-events-none color-muted"
    />

    <input
        class="input rounded-full"
        type="text"
        name="q"
        placeholder="{{ __('waterhole::forum.search-placeholder') }}"
        required
    >

    <div class="hide-if-invalid">
        <button
            type="submit"
            class="btn btn--icon btn--transparent btn--small color-accent"
            aria-label="{{ __('waterhole::forum.search-button') }}"
        >
            <x-waterhole::icon icon="heroicon-o-arrow-right"/>
        </button>
    </div>
</form>

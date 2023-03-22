<form
    action="{{ route('waterhole.search') }}"
    role="search"
    {{ $attributes->class('input-container') }}
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
        aria-label="{{ __('waterhole::forum.search-placeholder') }}"
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

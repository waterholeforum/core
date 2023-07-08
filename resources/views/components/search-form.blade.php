<form
    action="{{ route('waterhole.search') }}"
    role="search"
    {{ $attributes->class('input-container') }}
>
    @icon('tabler-search', ['class' => 'no-pointer color-muted'])

    <input
        class="pill"
        type="text"
        name="q"
        placeholder="{{ __('waterhole::forum.search-placeholder') }}"
        aria-label="{{ __('waterhole::forum.search-placeholder') }}"
        required
        data-hotkey="/"
    />

    <div class="hide-if-invalid">
        <button
            type="submit"
            class="btn btn--icon btn--transparent btn--sm color-accent"
            aria-label="{{ __('waterhole::forum.search-button') }}"
        >
            @icon('tabler-arrow-right')
        </button>
    </div>
</form>

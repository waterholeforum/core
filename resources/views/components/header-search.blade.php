{{--<ui-popup placement="bottom-end" class="header-search">--}}
{{--    <button class="btn btn--icon btn--transparent">--}}
{{--        <x-waterhole::icon icon="heroicon-o-search"/>--}}
{{--        <ui-tooltip>Search</ui-tooltip>--}}
{{--    </button>--}}

{{--    <div class="menu" hidden style="padding: .75rem">--}}
{{--        <form action="{{ route('waterhole.search') }}" class="lead row gap-xs">--}}
{{--            <input type="text" class="input" placeholder="Search" autofocus style="width: 20em" name="q">--}}
{{--            <button class="btn btn--primary">Go</button>--}}
{{--        </form>--}}
{{--    </div>--}}
{{--</ui-popup>--}}

<form action="{{ route('waterhole.search') }}" class="input-container" style="margin-right: var(--space-xs)">
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
    <button class="btn btn--icon btn--link hide-if-invalid">
        <x-waterhole::icon icon="heroicon-o-arrow-right"/>
    </button>
</form>

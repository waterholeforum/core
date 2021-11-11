<ui-popup placement="bottom-end" class="header-search">
    <button class="btn btn--icon btn--transparent">
        <x-waterhole::icon icon="heroicon-s-search"/>
        <ui-tooltip>Search</ui-tooltip>
    </button>

    <div class="menu" hidden style="padding: .75rem">
        <form action="{{ route('waterhole.search') }}" class="lead toolbar toolbar--nowrap">
            <input type="text" class="input" placeholder="Search" autofocus style="width: 20em" name="q">
            <button class="btn btn--primary">Go</button>
        </form>
    </div>
</ui-popup>

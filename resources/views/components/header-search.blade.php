<ui-popup placement="bottom-end">
    <button class="btn btn--icon btn--transparent">
        <x-waterhole::icon icon="heroicon-s-search"/>
        <ui-tooltip>Search</ui-tooltip>
    </button>

    <div class="menu" hidden style="padding: .75rem">
        <div class="toolbar" style="font-size: 1.2em">
            <input type="text" class="input" placeholder="Search" autofocus style="width: 20em">
            <button class="btn btn--primary">Go</button>
        </div>
        <div class="toolbar" style="margin-top: 1em">
        <label class="radio">
            <input type="radio" checked>
            In this discussion
        </label>
        <label class="radio">
            <input type="radio">
            All posts
        </label>
        </div>
    </div>
</ui-popup>

@auth
    <button class="btn btn--icon btn--transparent">
        <x-waterhole::icon icon="heroicon-o-mail"/>
        <ui-tooltip>Messages</ui-tooltip>
    </button>

    <button class="btn btn--icon btn--transparent">
        <x-waterhole::icon icon="heroicon-o-bell"/>
        <ui-tooltip>Notifications</ui-tooltip>
    </button>
@endauth

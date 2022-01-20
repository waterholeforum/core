<x-waterhole::nav-link
    label="Updates"
    icon="heroicon-o-refresh"
    route="waterhole.admin.updates"
>
    <div class="spacer"></div>
    <turbo-frame
        id="updates_count"
        src="{{ route('waterhole.admin.updates.list') }}"
    >
        <div class="loading-indicator loading-indicator--inline"></div>
    </turbo-frame>
</x-waterhole::nav-link>

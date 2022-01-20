<x-waterhole::admin title="Updates">
    <turbo-frame
        id="updates_list"
        src="{{ route('waterhole.admin.updates.list') }}"
        data-controller="updates"
        data-action="process:finish@document->updates#reload"
    >
        <div class="loading-indicator"></div>
    </turbo-frame>
</x-waterhole::admin>

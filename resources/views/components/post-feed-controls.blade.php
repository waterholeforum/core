<ui-popup placement="bottom-end">
    <button
        class="btn btn--icon btn--transparent"
        aria-label="{{ __('waterhole::system.controls-button') }}"
    >
        <x-waterhole::icon icon="tabler-settings"/>
    </button>

    <ui-menu class="menu" hidden>
        @components(Waterhole\Extend\PostFeedControls::build(), compact('feed', 'channel'))
    </ui-menu>
</ui-popup>

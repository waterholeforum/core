<div class="admin__version text-xs row gap-xs" style="margin-top: var(--space-md)">
    <a
        href="https://waterhole.dev"
        class="color-muted"
        target="_blank"
    >Waterhole {{ Waterhole::VERSION }}</a>

    <turbo-frame
        id="license"
        src="{{ route('waterhole.admin.license') }}"
        data-turbo-permanent
    ></turbo-frame>
</div>

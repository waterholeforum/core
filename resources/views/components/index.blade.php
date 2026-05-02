@php($globalSidebar = config('waterhole.design.global_sidebar'))

<div
    @class([
        'section container index-layout',
        'with-sidebar' => ! $globalSidebar,
    ])
>
    @unless ($globalSidebar)
        <div class="index-sidebar sidebar sidebar--sticky gap-x-md gap-y-lg">
            @components(resolve(\Waterhole\Extend\Ui\IndexPage::class)->sidebar, compact('channel'))
        </div>
    @endunless

    <div>
        {{ $slot }}
    </div>
</div>

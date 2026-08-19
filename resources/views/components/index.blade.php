@php($globalSidebar = config('waterhole.design.global_sidebar'))

<div
    @class([
        'section container index-layout',
        'with-sidebar' => ! $globalSidebar,
    ])
>
    @unless ($globalSidebar)
        <div class="index-sidebar sidebar sidebar--sticky gap-x-md gap-y-lg">
            @components(resolve(\Waterhole\Extend\Ui\IndexPage::class)->sidebar, compact('activeNode'))
        </div>
    @endunless

    <div class="stack gap-lg">
        @if ($breadcrumbs->count() > 1)
            <nav
                aria-label="{{ __('waterhole::forum.structure-breadcrumbs-label') }}"
            >
                <ol class="breadcrumb">
                    @foreach ($breadcrumbs as $crumb)
                        @php($content = $crumb->content)
                        <li @if ($loop->last) aria-current="page" @endif>
                            @if ($loop->last)
                                {{ $content->name }}
                            @else
                                <a href="{{ $content->url }}">
                                    {{ $content->name }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif

        <div @class(['stack gap-xl' => $content])>
            @if ($content)
                <x-waterhole::structure-header :content="$content" />

                <x-waterhole::structure-children :parent="$activeNode" />
            @endif

            {{ $slot }}
        </div>
    </div>
</div>

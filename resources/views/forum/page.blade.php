<x-waterhole::layout>
    <div class="section container">
        <div class="stack-xl" style="max-width: 80ch">
            <header class="stack-md">
                <div class="breadcrumb row gap-xs color-muted">
                    <a href="{{ route('waterhole.home') }}" class="row gap-xxs">
                        <x-waterhole::icon icon="heroicon-o-home"/>
                        Home
                    </a>
                    ›
                </div>

                <h1 data-page-target="title">{{ $page->name }}</h1>
            </header>

            <div class="content text-md">
                {{ $page->body_html }}
            </div>
        </div>
    </div>
</x-waterhole::layout>

<x-waterhole::layout>
    <div class="section container">
        <div class="stack gap-xl measure-regular">
            <header class="stack gap-xs">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route('waterhole.home') }}" class="with-icon">
                            <x-waterhole::icon icon="heroicon-o-home"/>
                            Home
                        </a>
                    </li>
                    <li aria-hidden="true"></li>
                </ol>

                <h1 data-page-target="title">{{ $page->name }}</h1>
            </header>

            <div class="content text-md">
                {{ $page->body_html }}
            </div>
        </div>
    </div>
</x-waterhole::layout>

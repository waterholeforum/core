<x-waterhole::layout>
    <div class="section container">
        <div class="stack gap-xl measure-regular">
            <div class="stack gap-xs">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{ route('waterhole.home') }}" class="with-icon">
                                <x-waterhole::icon icon="tabler-home"/>
                                Home
                            </a>
                        </li>
                        <li aria-hidden="true"></li>
                    </ol>
                </nav>

                <h1 data-page-target="title">{{ $page->name }}</h1>
            </div>

            <div class="content text-md">
                {{ $page->body_html }}
            </div>
        </div>
    </div>
</x-waterhole::layout>

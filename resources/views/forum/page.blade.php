<x-waterhole::layout>
    <div class="section container">
        <div class="stack-xl" style="max-width: 80ch">
            <h1 data-page-target="title">{{ $page->name }}</h1>

            <div class="content text-md">
                {{ $page->body_html }}
            </div>
        </div>
    </div>
</x-waterhole::layout>

<x-waterhole::layout>
    <x-waterhole::index>
        <div class="stack-xl" style="max-width: 80ch">
{{--            <h2 data-page-target="title">{{ $page->name }}</h2>--}}

            <div class="content text-md">
                {{ $page->body_html }}
            </div>
        </div>
    </x-waterhole::index>
</x-waterhole::layout>

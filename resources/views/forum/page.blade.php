<x-waterhole::layout :title="$page->name">
    <x-slot name="head">
        @unless ($page->structure->is_listed)
            <meta name="robots" content="noindex" />
        @endunless
    </x-slot>

    <x-waterhole::index>
        <div class="stack gap-xl measure card p-gutter">
            <h1 data-page-target="title">{{ $page->name }}</h1>

            <div class="content text-md">
                {{ $page->body_html }}
            </div>
        </div>
    </x-waterhole::index>
</x-waterhole::layout>

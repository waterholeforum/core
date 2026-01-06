<x-waterhole::layout
    :title="$page->name"
    :seo="[
        'description' => $page->body_html,
        'url' => route('waterhole.page', compact('page')),
        'noindex' => ! $page->structure->is_listed,
        'schema' => ['@type' => 'WebPage'],
    ]"
>
    <x-waterhole::index>
        <div class="stack gap-xl measure card p-gutter">
            <h1 data-page-target="title">{{ $page->name }}</h1>

            <div class="content text-md">
                {{ $page->body_html }}
            </div>
        </div>
    </x-waterhole::index>
</x-waterhole::layout>

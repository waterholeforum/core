<x-waterhole::forum-layout
    :title="$page->name"
    :active-node="$page->structure"
    :data-page="$page->slug"
    show-sidebar
    :seo="[
        'description' => filled($page->description) ? $page->description_html : $page->body_html,
        'url' => $page->url,
        'noindex' => ! $page->isListed(),
        'schema' => ['@type' => 'WebPage'],
    ]"
>
    <x-waterhole::index :active-node="$page->structure">
        @if ($page->body)
            <div
                class="card card__body content text-md"
                data-controller="lightbox"
            >
                {{ $page->body_html }}
            </div>
        @endif
    </x-waterhole::index>
</x-waterhole::forum-layout>

<x-waterhole::flag-container
    :subject="$post"
    :hide="$post->trashed()"
    {{
    $attributes
        ->class('card post-full')
        ->merge(resolve(Waterhole\Extend\Ui\PostAttributes::class)->build($post))
}}
>
    <article class="post-full__inner p-gutter stack gap-xl">
        <meta itemprop="headline" content="{{ $post->title }}" />
        <meta itemprop="datePublished" content="{{ $post->created_at?->toAtomString() }}" />
        @if ($post->edited_at)
            <meta itemprop="dateModified" content="{{ $post->edited_at?->toAtomString() }}" />
        @endif
        <meta itemprop="url" content="{{ $post->url }}" />
        <span itemprop="author" itemscope itemtype="https://schema.org/Person" hidden>
            <meta itemprop="name" content="{{ Waterhole\username($post->user) }}" />
            @if ($post->user)
                <meta itemprop="url" content="{{ $post->user->url }}" />
            @endif
        </span>

        @if ($post->trashed())
            <x-waterhole::alert class="bg-fill color-muted p-md" icon="tabler-trash">
                <x-waterhole::removed-banner :subject="$post">
                    <x-slot name="lead">
                        <strong>{{ __('waterhole::forum.post-removed-message') }}</strong>
                    </x-slot>
                </x-waterhole::removed-banner>
            </x-waterhole::alert>
        @endif

        <header class="post-header row wrap align-center gap-x-md gap-y-xl">
            @components(resolve(Waterhole\Extend\Ui\PostPage::class)->header, compact('post'))
        </header>

        <div class="post-body content text-md" data-controller="quotable" itemprop="text">
            {{ $post->body_html }}

            @can('waterhole.post.comment', $post)
                <a
                    href="{{ route('waterhole.posts.comments.create', compact('post')) }}"
                    class="quotable-button btn bg-emphasis no-select"
                    data-turbo-frame="@domid($post, 'comment_parent')"
                    data-quotable-target="button"
                    data-action="quotable#quoteSelectedText"
                    hidden
                >
                    @icon('tabler-quote')
                    <span>{{ __('waterhole::forum.quote-button') }}</span>
                </a>
            @endcan
        </div>

        <div class="row gap-xs text-md">
            @components(Waterhole\Extend\Ui\PostFooter::class, compact('post'))
        </div>

        @components(resolve(Waterhole\Extend\Ui\PostPage::class)->middle, compact('post'))
    </article>
</x-waterhole::flag-container>

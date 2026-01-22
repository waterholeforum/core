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
        @if ($post->trashed())
            <x-waterhole::alert class="bg-fill color-muted p-md" icon="tabler-trash">
                <div class="alert__message row gap-sm align-center nowrap">
                    <strong>{{ __('waterhole::forum.post-removed-message') }}</strong>

                    @can('waterhole.post.moderate', $post)
                        @if ($post->deletedBy)
                            <span class="user-label">
                                <x-waterhole::avatar :user="$post->deletedBy" />
                                <ui-tooltip>
                                    {{
                                        __('waterhole::forum.post-removed-tooltip', [
                                            'user' => Waterhole\username($post->deletedBy),
                                            'timestamp' => $post->deleted_at->toDayDateTimeString(),
                                        ])
                                    }}
                                </ui-tooltip>
                            </span>
                        @endif
                    @endcan

                    @if ($post->deleted_reason)
                        <span class="text-xxs">
                            {{
                                Lang::has($key = "waterhole::forum.report-reason-$post->deleted_reason")
                                    ? __($key)
                                    : Str::headline($post->deleted_reason)
                            }}
                        </span>
                    @endif
                </div>
            </x-waterhole::alert>
        @endif

        <header class="post-header row wrap align-center gap-x-md gap-y-xl">
            @components(resolve(Waterhole\Extend\Ui\PostPage::class)->header, compact('post'))
        </header>

        <div class="post-body content text-md" data-controller="quotable">
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

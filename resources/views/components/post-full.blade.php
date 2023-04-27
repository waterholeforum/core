<article {{ $attributes->class('post-full') }}>
    <div class="post-full__main stack gap-xl card p-gutter">
        <header class="post-header stack align-start gap-x-md gap-y-xl">
            @components(Waterhole\Extend\PostHeader::build(), compact('post'))
        </header>

        <div
            class="post-body content text-md"
            data-controller="quotable"
        >
            {{ Waterhole\emojify($post->body_html) }}

            @can('post.comment', $post)
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
            @components(Waterhole\Extend\PostFooter::build(), compact('post'))
        </div>

        @components(Waterhole\Extend\PostPage::build(), compact('post'))
    </div>
</article>

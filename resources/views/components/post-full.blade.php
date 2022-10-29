<article {{ $attributes->class('post-full with-sidebar') }}>
    <div class="post-full__main section stack gap-xl">
        <header class="post-header stack gap-xl">
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
                    <x-waterhole::icon icon="tabler-quote"/>
                    <span>{{ __('waterhole::forum.quote-button') }}</span>
                </a>
            @endcan
        </div>
    </div>

    <div
        class="sidebar sidebar--sticky sidebar--bottom overflow-visible gap-md"
        data-controller="watch-sticky"
    >
        <x-waterhole::action-menu :for="$post">
            <x-slot name="button">
                <button type="button" class="btn">
                    <x-waterhole::icon icon="tabler-settings"/>
                    <span>{{ __('waterhole::system.controls-button') }}</span>
                    <x-waterhole::icon icon="tabler-chevron-down"/>
                </button>
            </x-slot>
        </x-waterhole::action-menu>

        <div class="row gap-row-md gap-col-xs wrap">
            @components(Waterhole\Extend\PostFooter::build(), compact('post'))
        </div>
    </div>
</article>

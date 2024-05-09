<x-waterhole::layout :title="$post->title">
    <x-slot name="head">
        @unless ($post->channel->structure->is_listed)
            <meta name="robots" content="noindex" />
        @endunless
    </x-slot>

    <div
        class="post-page section container with-sidebar"
        data-controller="post-page"
        data-post-page-id-value="{{ $post->id }}"
        {{ new Illuminate\View\ComponentAttributeBag(Waterhole\Extend\PostAttributes::build($post)) }}
    >
        <div class="stack gap-lg measure">
            <div data-post-page-target="post" @if (!$comments->onFirstPage()) hidden @endif>
                <x-waterhole::post-full :post="$post" />
            </div>

            <section class="post-page__comments stack gap-md">
                @if ($comments->count())
                    <h2 class="h4" id="comments">
                        {{ __('waterhole::forum.post-comments-heading', ['count' => $post->comment_count]) }}
                    </h2>
                @endif

                <x-waterhole::infinite-scroll
                    :paginator="$comments"
                    divider
                    endless
                    class="comment-list card"
                >
                    @foreach ($comments as $i => $comment)
                        @if ($lastReadAt && $comment->created_at > $lastReadAt)
                            @once
                                <div class="divider color-activity" id="unread" tabindex="-1">
                                    {{ __('waterhole::forum.comments-unread-heading') }}
                                </div>
                            @endonce
                        @endif

                        <x-waterhole::comment-frame :comment="$comment" class="card__row" />
                    @endforeach
                </x-waterhole::infinite-scroll>
            </section>

            @if (! $comments->hasMorePages())
                <div class="stack gap-md" id="bottom" tabindex="-1">
                    @components(Waterhole\Extend\CommentsBottom::build(), compact('post'))
                </div>
            @endif

            @can('waterhole.post.comment', $post)
                <div id="reply" tabindex="-1"></div>
                <x-waterhole::composer :post="$post" data-turbo-permanent />
            @endcan
        </div>

        <div
            class="sidebar sidebar--sticky sidebar--bottom overflow-visible stack gap-lg justify-between"
            data-controller="watch-sticky"
        >
            <x-waterhole::post-sidebar :post="$post" />

            @if ($comments->total())
                <a
                    href="#comments"
                    class="with-icon color-muted text-xs weight-medium"
                    data-post-page-target="commentsLink"
                    hidden
                >
                    @icon('tabler-message-circle-2')
                    {{ __('waterhole::forum.post-comments-link', ['count' => $comments->total()]) }}
                </a>

                <ui-popup class="collapsible-nav stack" data-post-page-target="commentsPagination">
                    <button class="btn btn--transparent">
                        {{ __('waterhole::system.page-number-prefix') }}
                        <span data-post-page-target="currentPage">
                            {{ $comments->currentPage() }}
                        </span>
                        @icon('tabler-selector')
                    </button>

                    <div hidden class="drawer drawer--right">
                        <nav class="comments-pagination tabs tabs--vertical gap-sm">
                            <a class="tab with-icon" href="{{ $post->url }}#top">
                                @icon('tabler-chevrons-up', ['class' => 'icon--narrow'])
                                {{ __('waterhole::forum.original-post-link') }}
                            </a>

                            <div
                                class="scrollable-y stack comments-pagination__pages"
                                data-controller="scrollspy watch-scroll"
                            >
                                @for ($page = 1; $page <= $comments->lastPage(); $page++)
                                    <a
                                        class="tab"
                                        {{-- Exclude ?page=1 from the URL so that the page isn't needlessly reloaded. --}}
                                        href="{{ $page === 1 ? $post->url : $comments->url($page) }}#page_{{ $page }}"
                                        @if ($page == $comments->currentPage()) aria-current="page" @endif
                                    >
                                        {{ $page }}
                                    </a>
                                @endfor
                            </div>

                            <a
                                class="tab with-icon"
                                {{-- Exclude ?page=1 from the URL so that the page isn't needlessly reloaded. --}}
                                href="{{ $comments->lastPage() === 1 ? $post->url : $comments->url($comments->lastPage()) }}#bottom"
                            >
                                @icon('tabler-chevrons-down', ['class' => 'icon--narrow'])
                                {{ __('waterhole::system.pagination-last-link') }}
                            </a>
                        </nav>
                    </div>
                </ui-popup>
            @endif
        </div>
    </div>

    <x-turbo::stream-from
        :source="$post"
        :type="$post->channel->isPublic() ? 'channel' : 'private'"
    />
</x-waterhole::layout>

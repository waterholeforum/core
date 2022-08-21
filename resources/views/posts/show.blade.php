<x-waterhole::layout :title="$post->title">
    <div
        class="post-page container stack gap-lg"
        data-controller="post-page"
        data-post-page-id-value="{{ $post->id }}"
    >
        <div class="stack gap-xxxl">
            <div
                @if (!$comments->onFirstPage()) hidden @endif
                data-post-page-target="post"
            >
                <x-waterhole::post-full :post="$post"/>
            </div>

            <section
                class="post-page__comments with-sidebar"
                id="comments"
                tabindex="-1"
            >
                <div class="stack gap-lg">
                    <h2 class="h3">
                        {{ __('waterhole::forum.post-comment-count', ['count' => $post->comment_count]) }}
                    </h2>

                    <x-waterhole::infinite-scroll :paginator="$comments" divider endless>
                        @foreach ($comments as $i => $comment)
                            @if ($lastReadAt && $comment->created_at > $lastReadAt)
                                @once
                                    <div
                                        class="divider color-activity"
                                        id="unread"
                                        tabindex="-1"
                                    >{{ __('waterhole::forum.comments-unread-heading') }}</div>
                                @endonce
                            @endif

                            <turbo-frame id="@domid($comment)">
                                <x-waterhole::comment-full
                                    :comment="$comment"
                                    :data-index="$comments->firstItem() - 1 + $i"
                                />
                            </turbo-frame>
                        @endforeach

                        @if (!$comments->hasMorePages())
                            <div
                                class="stack gap-md"
                                data-post-page-target="bottom"
                                id="bottom"
                                tabindex="-1"
                            >
                                @components(Waterhole\Extend\CommentsBottom::build(), compact('post'))
                            </div>
                        @endif
                    </x-waterhole::infinite-scroll>
                </div>

                <div class="sidebar sidebar--sticky overflow-visible">
                    <div class="stack gap-lg">
                        <x-waterhole::follow-button :followable="$post"/>

                        @if ($comments->total())
                            <nav class="ruler tabs" data-controller="scrollspy">
                                <a
                                    class="tab with-icon"
                                    href="{{ $post->url }}#top"
                                >
                                    <x-waterhole::icon
                                        icon="heroicon-s-chevron-double-up"
                                        class="text-xs icon--narrow"
                                    />
                                    <span>{{ __('waterhole::system.pagination-first-link') }}</span>
                                </a>

                                @for ($page = 1; $page <= $comments->lastPage(); $page++)
                                    <a
                                        class="tab"
                                        href="{{ $comments->fragment('page_'.$page)->url($page) }}"
                                        @if ($page == $comments->currentPage()) aria-current="page" @endif
                                    >{{ $page }}</a>

                                    @if ($post->unread_count && $comments->total() - $post->unread_count < $page * $comments->perPage())
                                        <a
                                            class="divider color-activity"
                                            href="{{ $comments->fragment('unread')->url($page) }}"
                                        >{{ __('waterhole::forum.comments-unread-link') }}</a>
                                    @endif
                                @endfor

                                <a
                                    class="tab with-icon"
                                    href="{{ $comments->fragment('bottom')->url($comments->lastPage()) }}"
                                >
                                    <x-waterhole::icon
                                        icon="heroicon-s-chevron-double-down"
                                        class="text-xs icon--narrow"
                                    />
                                    <span>{{ __('waterhole::system.pagination-last-link') }}</span>
                                </a>
                            </nav>
                        @endif
                    </div>
                </div>
            </section>
        </div>

        @can('post.comment', $post)
            <x-waterhole::composer
                :post="$post"
                class="can-sticky"
                data-turbo-permanent
            />
        @endcan
    </div>
</x-waterhole::layout>

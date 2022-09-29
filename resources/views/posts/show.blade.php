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

            <section class="post-page__comments with-sidebar">
                <div class="stack gap-lg">

                    <h2
                        class="h3"
                        id="comments"
                        style="padding-top: var(--space-lg); margin-top: calc(-1 * var(--space-lg))"
                    >
                        <x-waterhole::post-comments-heading :post="$post"/>
                    </h2>

                    <x-waterhole::infinite-scroll
                        :paginator="$comments"
                        divider
                        endless
                    >
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
                                id="bottom"
                                tabindex="-1"
                            >
                                @components(Waterhole\Extend\CommentsBottom::build(), compact('post'))
                            </div>
                        @endif
                    </x-waterhole::infinite-scroll>
                </div>

                <div
                    class="sidebar sidebar--sticky sidebar--bottom overflow-visible stack gap-lg"
                    data-controller="watch-sticky"
                >
                    <x-waterhole::follow-button :followable="$post"/>

                    @if ($comments->total())
                        <nav class="ruler tabs" data-controller="scrollspy">
                            <a
                                class="tab with-icon"
                                href="{{ $post->url }}#top"
                            >
                                <x-waterhole::icon
                                    icon="tabler-chevrons-up"
                                    class="text-xs icon--narrow"
                                />
                                <span class="hide-xs">{{ __('waterhole::system.pagination-first-link') }}</span>
                            </a>

                            <div
                                class="scrollable stack ruler__pages"
                                style="max-height: 20rem"
                                data-scrollspy-target="container"
                            >
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
                            </div>

                            <a
                                class="tab with-icon"
                                href="{{ $comments->fragment('bottom')->url($comments->lastPage()) }}"
                            >
                                <x-waterhole::icon
                                    icon="tabler-chevrons-down"
                                    class="text-xs icon--narrow"
                                />
                                <span class="hide-xs">{{ __('waterhole::system.pagination-last-link') }}</span>
                            </a>
                        </nav>
                    @endif
                </div>
            </section>
        </div>

        @can('post.comment', $post)
            <x-waterhole::composer
                :post="$post"
                data-turbo-permanent
            />
        @endcan
    </div>

    <x-turbo-stream-from
        :source="$post"
        :type="in_array($post->channel->id, Waterhole\Models\Channel::allPermitted(null)) ? 'channel' : 'private'"
    />
</x-waterhole::layout>

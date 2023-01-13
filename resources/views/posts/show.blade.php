@php use Illuminate\View\ComponentAttributeBag;use Waterhole\Extend\PostAttributes; @endphp
<x-waterhole::layout :title="$post->title">
    <div
        class="post-page section container stack gap-lg"
        data-controller="post-page"
        data-post-page-id-value="{{ $post->id }}"
        {{ new ComponentAttributeBag(PostAttributes::build($post)) }}
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

                    <h2 class="h3" id="comments">
                        {{ __('waterhole::forum.post-comments-heading', ['count' => $post->comment_count]) }}
                    </h2>

                    <x-waterhole::infinite-scroll
                        :paginator="$comments"
                        divider
                        endless
                        class="comment-list"
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
                        <nav
                            class="comments-pagination tabs"
                            data-controller="scrollspy"
                        >
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
                                class="scrollable stack comments-pagination__pages"
                                data-scrollspy-target="container"
                            >
                                @for ($page = 1; $page <= $comments->lastPage(); $page++)
                                    <a
                                        class="tab"
                                        href="{{ $comments->fragment('page_'.$page)->url($page) }}"
                                        @if ($page == $comments->currentPage()) aria-current="page" @endif
                                    >{{ $page }}</a>
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
        :type="$post->channel->isPublic() ? 'channel' : 'private'"
    />
</x-waterhole::layout>

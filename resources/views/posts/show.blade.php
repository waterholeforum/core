<x-waterhole::layout :title="$post->title">
    <div
        class="post-page"
        data-controller="post-page"
        data-action="turbo:before-stream-render@document->post-page#beforeStreamRender"
        data-post-page-id-value="{{ $post->id }}"
    >
        <div class="container">
            <div
                @if (! $comments->onFirstPage()) hidden @endif
                data-action="turbo:frame-render@document->post-page#showPostOnFirstPage"
                data-post-page-target="post"
            >
                <x-waterhole::post-full :post="$post"/>
                <br><br><br><br>
            </div>

            <section class="post-comments" tabindex="-1" id="comments">

                <h2 style="margin-bottom: var(--space-lg); padding-top: var(--space-xl)">
                    {{ __('waterhole::forum.post-comment-count', ['count' => $post->comment_count]) }}
                </h2>

                <div class="with-sidebar-end">

                    <div class="post-comments__comments" id="@domid($post, 'comments')">
                        <turbo-frame id="page_{{ $comments->currentPage() }}" target="_top">
                            @if (! $comments->onFirstPage() && request()->query('direction') !== 'forwards')
                                <turbo-frame
                                    id="page_{{ $comments->currentPage() - 1 }}"
                                    src="{{ $comments->appends('direction', 'backwards')->previousPageUrl() }}"
                                    loading="lazy"
                                    class="next-page"
                                    target="_top"
                                    data-controller="load-backwards"
                                    data-action="
                                            turbo:before-fetch-response->load-backwards#lockScrollPosition
                                            turbo:frame-render->load-backwards#unlockScrollPosition"
                                >
                                    <div class="loading-indicator"></div>
                                </turbo-frame>
                            @endif

                            <div
                                id="page-{{ $comments->currentPage() }}"
                                tabindex="-1"
                            >
                                @if (! $comments->onFirstPage())
                                    <div class="divider">
                                        <span>Page {{ $comments->currentPage() }}</span>
                                    </div>
                                @endif

                                @foreach ($comments as $i => $comment)
                                    @if ($post->userState && $comment->created_at > $post->userState->last_read_at)
                                        @once
                                            <div
                                                class="divider post-comments__unread"
                                                id="unread"
                                                tabindex="-1"
                                            >
                                                <span>Unread</span>
                                            </div>
                                        @endonce
                                    @endif
                                    <turbo-frame id="@domid($comment)">
                                        <x-waterhole::comment-full
                                            :comment="$comment"
                                            :data-index="$comments->firstItem() - 1 + $i"
                                        />
                                    </turbo-frame>
                                @endforeach
                            </div>

                            @if ($comments->hasMorePages())
                                @if (request()->query('direction') !== 'backwards')
                                    <turbo-frame
                                        id="page_{{ $comments->currentPage() + 1 }}"
                                        src="{{ $comments->appends('direction', 'forwards')->nextPageUrl() }}"
                                        loading="lazy"
                                        class="next-page"
                                        target="_top"
                                    >
                                        <div class="loading-indicator"></div>
                                    </turbo-frame>
                                @endif
                            @else
                                <div id="@domid($post, 'bottom')" tabindex="-1" data-post-page-target="bottom"></div>
                            @endif
                        </turbo-frame>
                    </div>

                    <div
                        class=""
                        style="position: sticky; top: calc(var(--header-height) + var(--space-xl)); margin-left: var(--space-xxxl); width: 160px; flex-shrink: 0; padding: 0 0 0 var(--space-md); margin-bottom: 0"
                    >
                        <div class="toolbar ruler">

                            <div
                                style="margin-bottom: var(--space-sm)"
                            >
                                <x-waterhole::follow-button
                                    :followable="$post"
                                />
                            </div>

                            @if ($comments->total())

                                <nav
                                    class="pagination tabs"
                                    data-controller="scrollspy"
                                    data-action="scroll@window->scrollspy#onScroll"
                                >

                                    <a
                                        class="tab"
                                        href="{{ $post->url }}#top"
                                        style="margin-bottom: var(--space-sm)"
                                    >
                                        <x-waterhole::icon
                                            icon="heroicon-s-chevron-double-up"
                                            style="font-size:90%; margin-left: -3px"
                                        />
                                        <span>First</span>
                                    </a>

                                    @for ($page = 1; $page <= $comments->lastPage(); $page++)
                                        <a
                                            class="tab"
                                            href="{{ $comments->appends('direction', null)->fragment('page-'.$page)->url($page) }}"
                                            @if ($page == $comments->currentPage()) aria-current="page" @endif
                                        >{{ $page }}</a>

                                        @if ($post->unread_count && $comments->total() - $post->unread_count < $page * $comments->perPage())
                                            <a
                                                class="divider ruler__unread"
                                                href="{{ $comments->appends('direction', null)->fragment('unread')->url($page) }}"
                                            >Unread</a>
                                        @endif
                                    @endfor

                                    <a
                                        class="tab"
                                        href="{{ $comments->fragment(Tonysm\TurboLaravel\dom_id($post, 'bottom'))->url($comments->lastPage()) }}"
                                        style="margin-top: var(--space-sm)"
                                    >
                                        <x-waterhole::icon
                                            icon="heroicon-s-chevron-double-down"
                                            style="font-size:90%; margin-left: -3px"
                                        />
                                        <span>Last</span>
                                    </a>
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>

            </section>

            @can('reply', $post)
                <x-waterhole::composer
                    :post="$post"
                    class="can-sticky"
                    data-turbo-permanent
                />
            @endcan
        </div>
    </div>
</x-waterhole::layout>

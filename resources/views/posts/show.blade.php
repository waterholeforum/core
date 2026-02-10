@php
    $imageUpload = $post->attachments->first(fn ($upload) => $upload->type && str_starts_with($upload->type, 'image/'));
    $ogImage = $imageUpload ? Storage::disk('public')->url('uploads/' . $imageUpload->filename) : null;
@endphp

<x-waterhole::layout
    :title="$post->title"
    :seo="[
        'description' => $post->body_text,
        'url' => $post->url,
        'type' => 'article',
        'image' => $ogImage,
        'noindex' => ! $post->channel->structure->is_listed,
        'schema' => false,
    ]"
>
    <div
        class="post-page section container with-sidebar"
        data-controller="post-page"
        data-post-page-id-value="{{ $post->id }}"
        itemscope
        itemtype="https://schema.org/DiscussionForumPosting"
        itemid="{{ $post->url }}"
        {{ new Illuminate\View\ComponentAttributeBag(resolve(\Waterhole\Extend\Ui\PostAttributes::class)->build($post)) }}
    >
        <meta itemprop="commentCount" content="{{ $post->comment_count }}" />

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

                        <x-waterhole::comment-frame
                            :comment="$comment"
                            class="card__row"
                            with-structured-data
                        />
                    @endforeach
                </x-waterhole::infinite-scroll>
            </section>

            @if (! $comments->hasMorePages())
                <div class="stack gap-md" id="bottom" tabindex="-1">
                    @components(resolve(\Waterhole\Extend\Ui\PostPage::class)->bottom, compact('post'))
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

            <div class="stack gap-lg" data-post-page-target="commentsLinks">
                @if ($comments->total())
                    <div class="tabs tabs--vertical gap-xxs">
                        <a href="#comments" class="tab with-icon">
                            @icon('tabler-message-circle-2')
                            {{ __('waterhole::forum.post-comments-link', ['count' => $comments->total()]) }}
                        </a>

                        <a
                            {{-- Exclude ?page=1 from the URL so that the page isn't needlessly reloaded. --}}
                            href="{{ $lastLink = ($comments->lastPage() === 1 ? $post->url : $comments->url($comments->lastPage())) . '#bottom' }}"
                            class="tab with-icon hide-md-down"
                        >
                            @icon('tabler-chevrons-down')
                            {{ __('waterhole::system.pagination-last-link') }}
                        </a>
                    </div>
                @endif

                @if ($headings->count() > 1)
                    <div class="post-headings tabs tabs--vertical gap-xxs hide-md-down">
                        <div
                            class="post-headings__tabs scrollable-y stack"
                            data-controller="scrollspy watch-scroll"
                        >
                            @foreach ($headings as $heading)
                                <a
                                    href="#{{ $heading['id'] }}"
                                    @class([
                                        'tab weight-normal text-xxs',
                                        'post-headings__tab--h3' => $heading['level'] === 'h3',
                                    ])
                                >
                                    {{ Waterhole\emojify($heading['text']) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            @if ($comments->total())
                <ui-popup
                    class="collapsible-nav stack"
                    data-post-page-target="commentsPagination"
                    hidden
                >
                    <button class="btn btn--transparent text-xs">
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

                            <a class="tab with-icon" href="{{ $lastLink }}">
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

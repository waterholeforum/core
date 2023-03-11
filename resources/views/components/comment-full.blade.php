<article
    {{ $attributes->class('comment')->merge(Waterhole\Extend\CommentAttributes::build($comment)) }}
    data-comment-id="{{ $comment->id }}"
    data-parent-id="{{ $comment->parent?->id }}"
    data-controller="comment"
    tabindex="-1"
>
    <div class="comment__main stack gap-md">
        <header class="comment__header">
            @components(Waterhole\Extend\CommentHeader::build(), compact('comment'))

            <x-waterhole::attribution
                :user="$comment->user"
                :date="$comment->created_at"
                :permalink="$comment->url"
            />

            @if ($comment->parent)
                <div
                    class="comment__parent"
                    data-action="
                        mouseenter->comment#highlightParent
                        mouseleave->comment#stopHighlightingParent
                        click->comment#stopHighlightingParent
                    "
                >
                    <a
                        href="{{ $comment->parent->post_url }}"
                        class="with-icon"
                        data-turbo-frame="_top"
                    >
                        <x-waterhole::icon
                            icon="tabler-arrow-back-up"
                            class="rotate-180"
                        />
                        <span>
                            {{ __('waterhole::forum.comment-in-reply-to-link') }}
                            <span class="user-label">
                                <x-waterhole::avatar :user="$comment->parent->user"/>
                                <span>{{ Waterhole\username($comment->parent->user) }}</span>
                            </span>
                        </span>
                    </a>

                    <ui-tooltip
                        placement="top-start"
                        tooltip-class="tooltip comment__parent-tooltip"
                        data-comment-target="parentTooltip"
                        hidden
                    >
                        <div class="comment">
                            <x-waterhole::attribution
                                :user="$comment->parent->user"
                                :date="$comment->parent->created_at"
                            />

                            <div class="content">
                                {!! Waterhole\emojify(Waterhole\truncate_html($comment->parent->body, 300)) !!}
                            </div>
                        </div>
                    </ui-tooltip>
                </div>
            @endif
        </header>

        <div
            class="comment__body content @if ($truncate) content--compact @endif"
            data-controller="quotable"
        >
            @if ($truncate)
                <x-waterhole::truncate :html="$comment->body_html">
                    <p>
                        <a href="{{ $comment->post_url }}" class="weight-bold">
                            {{ __('waterhole::forum.post-read-more-link') }}
                        </a>
                    </p>
                </x-waterhole::truncate>
            @else
                {{ Waterhole\emojify($comment->body_html) }}
            @endif

            @can('post.comment', $comment->post)
                <a
                    href="{{ route('waterhole.posts.comments.create', [
                        'post' => $comment->post,
                        'parent' => $comment->id
                    ]) }}"
                    class="quotable-button btn bg-emphasis no-select"
                    data-turbo-frame="@domid($comment->post, 'comment_parent')"
                    data-quotable-target="button"
                    data-action="quotable#quoteSelectedText"
                    hidden
                >
                    <x-waterhole::icon icon="tabler-quote"/>
                    <span>{{ __('waterhole::forum.quote-button') }}</span>
                </a>
            @endcan
        </div>

        <footer class="comment__footer row gap-xs wrap">
            @components(Waterhole\Extend\CommentFooter::build(), compact('comment', 'withReplies'))

            <div class="row wrap push-end">
                @components(Waterhole\Extend\CommentActions::build(), compact('comment', 'withReplies'))

                <x-waterhole::action-menu
                    :for="$comment"
                    placement="bottom-end"
                />
            </div>
        </footer>
    </div>

    <turbo-frame
        id="@domid($comment, 'replies')"
        @unless ($withReplies) hidden @endunless
    >
        @if ($withReplies)
            @if (count($comment->children))
                <ol
                    role="list"
                    tabindex="-1"
                    class="comment__replies comment-list card text-xs"
                >
                    @foreach ($comment->children as $child)
                        <li>
                            <x-waterhole::comment-frame :comment="$child"/>
                        </li>
                    @endforeach
                </ol>
            @endif
        @else
            <x-waterhole::spinner class="spinner--block"/>
        @endif
    </turbo-frame>
</article>

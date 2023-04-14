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
                        @icon('tabler-corner-down-right')
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
            class="comment__body content @if ($truncate) content--compact truncated @endif"
            data-controller="quotable @if ($truncate) truncated @endif"
        >
            {{ Waterhole\emojify($comment->body_html) }}

            @if ($truncate)
                <button
                    type="button"
                    class="truncated__expander link weight-bold"
                    hidden
                    data-truncated-target="expander"
                    data-action="truncated#expand"
                >
                    {{ __('waterhole::system.show-more-button') }}
                </button>
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
                    @icon('tabler-quote')
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
                    class="comment__replies comment-list card bg-fill text-xs"
                >
                    @foreach ($comment->children as $child)
                        <li class="card__row">
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

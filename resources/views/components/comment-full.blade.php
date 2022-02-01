<div
    role="article"
    {{ $attributes->class([
        'comment',
        Waterhole\Extend\CommentClasses::build($comment),
    ]) }}
    data-comment-id="{{ $comment->id }}"
    data-parent-id="{{ $comment->parent?->id }}"
    data-controller="comment"
    tabindex="-1"
>
    <div class="comment__main">
        <header class="comment__header">
            <x-waterhole::attribution
                :user="$comment->user"
                :date="$comment->created_at"
            />

            @if ($comment->parent)
                <div
                    class="comment__parent"
                    data-action="mouseenter->comment#highlightParent mouseleave->comment#stopHighlightingParent click->comment#stopHighlightingParent"
                >
                    <a
                        href="{{ $comment->parent->post_url }}"
                        class="with-icon"
                        data-turbo-frame="_top"
                    >
                        <x-waterhole::icon icon="heroicon-s-reply" class="rotate-180"/>
                        <span>
                            In reply to
                            <span class="user-label">
                                <x-waterhole::avatar :user="$comment->parent->user"/>
                                <span>{{ $comment->parent->user?->name ?: 'Anonymous' }}</span>
                            </span>
                        </span>
                    </a>

                    <ui-tooltip
                        placement="top-start"
                        tooltip-class="tooltip comment__parent-preview"
                        data-comment-target="parentTooltip"
                        hidden
                    >
                        <div class="comment">
                            <x-waterhole::attribution
                                :user="$comment->parent->user"
                                :date="$comment->parent->created_at"
                            />

                            <div class="content">
                                {!! Str::limit(strip_tags($comment->parent->body), 300) !!}
                            </div>
                        </div>
                    </ui-tooltip>
                </div>
            @endif
        </header>

        <div
            class="comment__body content"
            data-controller="quotable"
        >
            {{ emojify($comment->body_html) }}

            <a
                href="{{ route('waterhole.posts.comments.create', [
                    'post' => $comment->post,
                    'parent' => $comment->id
                ]) }}"
                class="quotable-button btn btn--tooltip"
                data-turbo-frame="@domid($comment->post, 'comment_parent')"
                data-quotable-target="button"
                data-action="quotable#quoteSelectedText"
                hidden
            >
                <x-waterhole::icon icon="heroicon-o-annotation"/>
                <span>Quote</span>
            </a>
        </div>

        <footer class="comment__footer toolbar">
            @components(Waterhole\Extend\CommentFooter::build(), compact('comment', 'withReplies'))

            <x-waterhole::action-menu
                :for="$comment"
                :button-attributes="['class' => 'btn--small']"
                placement="bottom-end"
                class="comment__control"
            />
        </footer>
    </div>

    <turbo-frame id="@domid($comment, 'replies')" @unless ($withReplies) hidden @endunless>
        @if ($withReplies)
            @if (count($comment->children))
                <ol role="list" tabindex="-1" class="comment__replies">
                    @foreach ($comment->children as $child)
                        <li>
                            <turbo-frame id="@domid($child)">
                                <x-waterhole::comment-full :comment="$child"/>
                            </turbo-frame>
                        </li>
                    @endforeach
                </ol>
            @endif
        @else
            <div class="loading-indicator"></div>
        @endif
    </turbo-frame>
</div>

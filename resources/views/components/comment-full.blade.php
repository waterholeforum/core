<x-waterhole::flag-container :subject="$comment" :hide="$comment->trashed()" {{ $attributes }}>
    <article
        {{ (new Illuminate\View\ComponentAttributeBag())->class('comment')->merge(resolve(Waterhole\Extend\Ui\CommentAttributes::class)->build($comment)) }}
        data-comment-id="{{ $comment->id }}"
        data-parent-id="{{ $comment->parent?->id }}"
        data-controller="comment"
        tabindex="-1"
    >
        @if ($comment->trashed())
            <div class="comment__removed row gap-xxs color-muted">
                <button
                    class="btn btn--sm btn--transparent btn--start"
                    data-action="comment#toggleExpanded"
                >
                    @icon('tabler-trash')
                    {{ __('waterhole::forum.comment-removed-message') }}
                    @icon('tabler-chevron-right', ['class' => 'icon--narrow text-xxs'])
                </button>

                @can('waterhole.comment.moderate', $comment)
                    @if ($comment->deletedBy)
                        <span class="user-label">
                            <x-waterhole::avatar :user="$comment->deletedBy" />
                            <ui-tooltip>
                                {{
                                    __('waterhole::forum.comment-removed-tooltip', [
                                        'user' => Waterhole\username($comment->deletedBy),
                                        'timestamp' => $comment->deleted_at->toDayDateTimeString(),
                                    ])
                                }}
                            </ui-tooltip>
                        </span>
                    @endif
                @endcan

                @if ($comment->deleted_reason)
                    <span class="text-xxs">
                        {{
                            Lang::has($key = "waterhole::forum.report-reason-$comment->deleted_reason-label")
                                ? __($key)
                                : Str::headline($comment->deleted_reason)
                        }}
                    </span>
                @endif

                <x-waterhole::action-menu
                    :for="$comment"
                    placement="bottom-end"
                    class="push-end"
                />
            </div>
        @endif

        <div class="comment__main stack gap-md">
            <header class="comment__header">
                @components(resolve(Waterhole\Extend\Ui\CommentComponent::class)->header, compact('comment'))

                <x-waterhole::attribution
                    :user="$comment->user"
                    :date="$comment->created_at"
                    :edit-date="$comment->edited_at"
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
                                    <x-waterhole::avatar :user="$comment->parent->user" />
                                    <span>
                                        {{ Waterhole\username($comment->parent->user) }}
                                    </span>
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
                                <div class="comment__inner">
                                    <x-waterhole::attribution
                                        :user="$comment->parent->user"
                                        :date="$comment->parent->created_at"
                                    />

                                    <div class="content">
                                        {{ Waterhole\emojify(Str::limit($comment->parent->body_text, 200)) }}
                                    </div>
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
                {{ $comment->body_html }}

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

                @if (! $comment->trashed())
                    @can('waterhole.post.comment', $comment->post)
                        <a
                            href="{{
                                route('waterhole.posts.comments.create', [
                                    'post' => $comment->post,
                                    'parent' => $comment->id,
                                ])
                            }}"
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
                @endif
            </div>

            <footer class="comment__footer row gap-xs wrap">
                @components(resolve(Waterhole\Extend\Ui\CommentComponent::class)->footer, compact('comment', 'withReplies'))

                <div class="row wrap push-end">
                    @components(resolve(Waterhole\Extend\Ui\CommentComponent::class)->buttons, compact('comment', 'withReplies'))

                    @if (! $comment->trashed())
                        <x-waterhole::action-menu :for="$comment" placement="bottom-end" />
                    @endif
                </div>
            </footer>
        </div>

        <turbo-frame
            id="@domid($comment, 'replies')"
            class="busy-spinner"
            @unless ($withReplies) hidden @endunless
        >
            @if ($withReplies)
                @if (count($comment->children))
                    <ol
                        role="list"
                        tabindex="-1"
                        class="comment__replies comment-list card bg-fill-soft text-xs"
                    >
                        @foreach ($comment->children as $child)
                            <li class="card__row">
                                <x-waterhole::comment-frame :comment="$child" />
                            </li>
                        @endforeach
                    </ol>
                @endif
            @endif
        </turbo-frame>
    </article>
</x-waterhole::flag-container>

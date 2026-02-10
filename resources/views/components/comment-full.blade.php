<x-waterhole::flag-container :subject="$comment" :hide="$comment->trashed()" {{ $attributes }}>
    <article
        {{
            (new Illuminate\View\ComponentAttributeBag())
                ->class('comment')
                ->merge(
                    $withStructuredData
                        ? [
                            'itemprop' => 'comment',
                            'itemscope' => true,
                            'itemtype' => 'https://schema.org/Comment',
                        ]
                        : [],
                )
                ->merge(resolve(Waterhole\Extend\Ui\CommentAttributes::class)->build($comment))
        }}
        data-comment-id="{{ $comment->id }}"
        data-parent-id="{{ $comment->parent?->id }}"
        data-controller="comment"
        tabindex="-1"
    >
        @if ($withStructuredData)
            <meta itemprop="datePublished" content="{{ $comment->created_at?->toAtomString() }}" />
            @if ($comment->edited_at)
                <meta
                    itemprop="dateModified"
                    content="{{ $comment->edited_at?->toAtomString() }}"
                />
            @endif

            <meta itemprop="url" content="{{ $comment->url }}" />
            <span itemprop="author" itemscope itemtype="https://schema.org/Person" hidden>
                <meta itemprop="name" content="{{ Waterhole\username($comment->user) }}" />
                @if ($comment->user)
                    <meta itemprop="url" content="{{ $comment->user->url }}" />
                @endif
            </span>
        @endif

        @if ($comment->trashed())
            <x-waterhole::removed-banner :subject="$comment">
                <x-slot name="lead">
                    <div class="comment__icon">
                        @icon('tabler-trash')
                    </div>

                    <button
                        class="btn btn--sm btn--transparent btn--start btn--end -my-sm"
                        data-action="comment#toggleExpanded"
                    >
                        {{ __('waterhole::forum.comment-removed-message') }}
                        @icon('tabler-chevron-right', ['class' => 'icon--narrow text-xxs'])
                    </button>
                </x-slot>

                <x-slot name="actions">
                    <x-waterhole::action-menu
                        :for="$comment"
                        placement="bottom-end"
                        class="-my-sm"
                    />
                </x-slot>
            </x-waterhole::removed-banner>
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
                @if ($withStructuredData) itemprop="text" @endif
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

                    <x-waterhole::action-button
                        :for="$comment"
                        :action="Waterhole\Actions\Bookmark::class"
                        class="btn btn--sm btn--transparent btn--icon"
                        icon
                    />

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
                                <x-waterhole::comment-frame
                                    :comment="$child"
                                    :with-structured-data="$withStructuredData"
                                />
                            </li>
                        @endforeach
                    </ol>
                @endif
            @endif
        </turbo-frame>
    </article>
</x-waterhole::flag-container>

<turbo-frame
    id="composer"
    {{ $attributes->class(['composer stack', 'is-open has-draft' => $hasDraft]) }}
    data-controller="composer watch-sticky"
    data-turbo-prefetch="false"
    data-action="
        turbo:frame-render->composer#frameRender
        quotable:quote-text@document->composer#open
    "
>
    <div class="composer__inner grow stack">
        <a
            href="{{ route('waterhole.posts.comments.create', compact('post', 'parent')) }}"
            class="composer__placeholder row gap-sm color-muted grow align-center"
            data-action="composer#placeholderClick"
            data-hotkey="r"
        >
            <x-waterhole::avatar :user="Auth::user()" class="icon text-lg" />
            <span>
                {{
                    $parent
                        ? __('waterhole::forum.composer-reply-to-placeholder', Waterhole\user_variables($parent->user))
                        : __('waterhole::forum.composer-placeholder')
                }}
            </span>
        </a>

        <form
            class="composer__form stack full-height"
            action="{{ route('waterhole.posts.comments.store', ['post' => $post]) }}"
            method="POST"
            data-controller="draft"
            data-action="
                input->draft#queue
                change->draft#queue
                focusout->draft#queue
                turbo:submit-start->draft#submitStart
                turbo:submit-end->draft#submitEnd
            "
        >
            @csrf

            <div
                class="composer__handle js-only"
                data-action="pointerdown->composer#startResize"
            ></div>

            <div class="composer__toolbar row">
                <button
                    type="button"
                    class="btn btn--transparent btn--icon composer__collapse"
                    data-action="composer#close"
                >
                    @icon('tabler-chevron-down')
                    <ui-tooltip>
                        {{ __('waterhole::forum.composer-collapse-button') }}
                    </ui-tooltip>
                </button>

                <button
                    type="button"
                    class="btn btn--transparent btn--icon composer__expand"
                    data-action="composer#open"
                >
                    @icon('tabler-chevron-up')
                    <ui-tooltip>
                        {{ __('waterhole::forum.composer-expand-button') }}
                    </ui-tooltip>
                </button>

                <div class="h5 overflow-ellipsis composer__title px-sm">
                    {{ __('waterhole::forum.create-comment-title') }}
                </div>

                {{--
                    [complete] is required to prevent this frame from automatically
                    reloading when the composer is reset after posting a comment
                --}}
                <turbo-frame
                    class="composer__parent nowrap row gap-xs text-xs pill bg-warning-soft pl-xs"
                    id="@domid($post, 'comment_parent')"
                    complete
                >
                    @if ($parent)
                        <input type="hidden" name="parent_id" value="{{ $parent->id }}" />

                        <a
                            href="{{ $parent->post_url }}"
                            data-turbo-frame="_top"
                            class="color-inherit with-icon"
                        >
                            @icon(
                                'tabler-share-3',
                                [
                                    'class' => 'flip-horizontal',
                                    'aria-label' => __('waterhole::forum.composer-replying-to-label'),
                                ]
                            )
                            <x-waterhole::user-label :user="$parent->user" />
                        </a>

                        <button class="btn btn--sm btn--transparent btn--icon" name="parent_id">
                            @icon('tabler-x')
                            <ui-tooltip>
                                {{ __('waterhole::forum.composer-clear-reply-button') }}
                            </ui-tooltip>
                        </button>
                    @endif
                </turbo-frame>

                <div class="grow"></div>

                <x-waterhole::draft-controls
                    class="composer__draft-controls mr-sm"
                    :saved="$hasDraft"
                    :action="route('waterhole.posts.draft', compact('post'))"
                />

                <button
                    class="btn bg-accent composer__submit"
                    name="commit"
                    value="1"
                    data-hotkey="Mod+Enter"
                    data-hotkey-scope="new-comment"
                >
                    {{ __('waterhole::forum.composer-submit') }}
                </button>
            </div>

            <x-waterhole::text-editor
                name="body"
                :value="$body"
                :placeholder="__('waterhole::forum.composer-placeholder')"
                id="new-comment"
                data-action="quotable:quote-text@document->text-editor#insertQuote"
                class="grow"
                :user-lookup-url="route('waterhole.user-lookup', ['post' => $post->id])"
            />
        </form>
    </div>

    @if ($errors->any())
        <turbo-stream action="alert">
            <template>
                <x-waterhole::alert type="danger" data-key="composer">
                    {{ $errors->first() }}
                </x-waterhole::alert>
            </template>
        </turbo-stream>
    @endif
</turbo-frame>

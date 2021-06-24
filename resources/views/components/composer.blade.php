<turbo-frame
    id="composer"
    {{ $attributes->class('composer stack') }}
    data-controller="composer watch-sticky"
    data-action="
        turbo:before-fetch-request->composer#open
        turbo:frame-render->composer#open
        comment:quote-text@document->text-editor#insertQuote
        turbo:submit-end->composer#submitEnd
    "
>
    <a
        href="{{ route('waterhole.posts.comments.create', compact('post', 'parent')) }}"
        class="composer__placeholder row gap-md color-muted"
        data-action="composer#placeholderClick"
        data-hotkey="r"
    >
        <x-waterhole::avatar :user="Auth::user()"/>
        <span>{{
            $parent
                ? __('waterhole::forum.composer-reply-to-placeholder', Waterhole\user_variables($parent->user))
                : __('waterhole::forum.composer-placeholder')
        }}</span>
    </a>

    <form
        class="composer__form stack full-height"
        action="{{ route('waterhole.posts.comments.store', ['post' => $post]) }}"
        method="POST"
    >
        @csrf

        <div
            class="composer__handle js-only"
            data-action="mousedown->composer#startResize"
        ></div>

        <div class="composer__toolbar row gap-xs">
            <button
                type="button"
                class="btn btn--transparent btn--icon composer__close"
                data-action="composer#close"
                data-hotkey="Escape"
                data-hotkey-scope="new-comment"
            >
                <x-waterhole::icon icon="tabler-chevron-down"/>
            </button>

            <div class="h5 overflow-ellipsis">{{ __('waterhole::forum.create-comment-title') }}</div>

            <turbo-frame
                class="composer__parent nowrap row gap-xs text-xs rounded-full bg-warning-soft"
                id="@domid($post, 'comment_parent')"
            >
                @if ($parent)
                    <input type="hidden" name="parent_id" value="{{ $parent->id }}">

                    <a href="{{ $parent->post_url }}" data-turbo-frame="_top" class="color-inherit">
                        {{ __('waterhole::forum.composer-replying-to-label') }}
                        <x-waterhole::user-label :user="$parent->user"/>
                    </a>

                    <button
                        class="btn btn--sm btn--transparent btn--icon"
                        name="parent_id"
                    >
                        <x-waterhole::icon icon="tabler-x"/>
                        <ui-tooltip>{{ __('waterhole::forum.composer-clear-reply-button') }}</ui-tooltip>
                    </button>
                @endif
            </turbo-frame>

            <div class="grow"></div>

            @if ($errors->any())
                <div class="color-danger weight-medium text-xs animate-shake">
                    {{ $errors->first() }}
                </div>
            @endif

            <button
                class="btn bg-accent"
                name="commit"
                value="1"
                data-hotkey="Meta+Enter,Ctrl+Enter"
                data-hotkey-scope="new-comment"
            >
                {{ __('waterhole::forum.composer-submit') }}
            </button>
        </div>

        <x-waterhole::text-editor
            name="body"
            :value="old('body')"
            placeholder="{{ __('waterhole::forum.composer-placeholder') }}"
            id="new-comment"
            data-action="quotable:quote-text@document->text-editor#insertQuote"
            class="grow"
        />
    </form>
</turbo-frame>

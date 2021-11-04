<turbo-frame
    {{ $attributes->class('composer') }}
    data-controller="composer watch-sticky"
    data-action="turbo:before-fetch-request->composer#open
        turbo:frame-render->composer#open
        comment:quote-text@document->text-editor#insertQuote
        turbo:submit-end->composer#submitEnd"
>
    <a
        href="{{ route('waterhole.posts.comments.create', compact('post', 'parent')) }}"
        class="composer__placeholder"
        data-action="composer#placeholderClick"
        data-hotkey="r"
    >
        <x-waterhole::avatar :user="Auth::user()"/>
        <span>{{ $parent ? 'Reply to '.($parent->user->name ?? 'Anonymous').'...' : 'Write a comment...' }}</span>
    </a>

    <form
        class="composer__form"
        action="{{ route('waterhole.posts.comments.store', ['post' => $post]) }}"
        method="POST"
    >
        @csrf

        <div
            class="composer__handle"
            data-composer-target="handle"
            data-action="mousedown->composer#startResize"
            hidden
        ></div>

        <div class="composer__toolbar toolbar">
            <button
                type="button"
                class="btn btn--transparent btn--icon composer__close"
                data-action="composer#close"
                data-hotkey="Escape"
                data-hotkey-scope="new-comment"
            >
                <x-waterhole::icon icon="heroicon-o-chevron-down"/>
            </button>

            <div class="h4">Write a Comment</div>

            <turbo-frame
                class="composer__parent"
                id="@domid($post, 'comment_parent')"
            >
                @if ($parent)
                    <input type="hidden" name="parent_id" value="{{ $parent->id }}">

                    <a href="{{ $parent->post_url }}" data-turbo-frame="_top">
                        Replying to <x-waterhole::user-label :user="$parent->user"/>
                    </a>

                    <button
                        class="btn btn--small btn--transparent btn--icon"
                        name="parent_id"
                    >
                        <x-waterhole::icon icon="heroicon-o-x"/>
                    </button>
                @endif
            </turbo-frame>

            <div class="spacer"></div>

            @if ($errors->any())
                <div class="text-danger text-xs animate-shake">
                    {{ $errors->first() }}
                </div>
            @endif

            <button
                class="btn btn--primary"
                name="commit"
                data-hotkey="Meta+Enter,Ctrl+Enter"
                data-hotkey-scope="new-comment"
            >
                Post
            </button>
        </div>

        <x-waterhole::text-editor
            name="body"
            :value="old('body')"
            placeholder="Write a comment..."
            id="new-comment"
            data-action="comment:quote-text@document->text-editor#insertQuote"
        />
    </form>
</turbo-frame>

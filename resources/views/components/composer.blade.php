<turbo-frame
    id="reply"
    {{ $attributes->class(['composer', 'is-open' => $open]) }}
    data-controller="composer watch-sticky"
    data-turbo-permanent
>
    <a
        href="{{ route('waterhole.posts.comments.create', compact('post')) }}"
        class="composer__placeholder"
        data-action="composer#open"
    >
        <x-waterhole::avatar :user="Auth::user()"/>
        <span>Write a comment...</span>
    </a>

    <form
        class="composer__form"
        action="{{ route('waterhole.posts.comments.store', ['post' => $post]) }}"
        method="POST"
    >
        @csrf

        <div
            class="composer__toolbar toolbar"
            data-composer-target="handle"
            data-action="mousedown->composer#startResize"
        >
            <button type="button" class="btn btn--transparent btn--icon" data-action="composer#close">
                <x-waterhole::icon icon="heroicon-o-x"/>
            </button>
            <div class="h4">Write a Comment</div>
            <div class="spacer"></div>

            @if ($errors->any())
                <div class="text-danger text-xs animate-shake">
                    {{ $errors->first() }}
                </div>
            @endif

            <button class="btn btn--primary">
                Post
            </button>
        </div>

        <x-waterhole::text-editor
            name="body"
            :value="old('body')"
            placeholder="Write a comment..."
            id="new-comment"
        />
    </form>
</turbo-frame>

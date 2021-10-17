@props(['post'])

@can('reply', $post)
    <form
        action="{{ route('waterhole.posts.comments.store', ['post' => $post]) }}"
        method="POST"
        id="reply"
        class="stack"
    >
        @csrf

        <x-waterhole::validation-errors :errors="$errors"/>

        <x-waterhole::field name="body">
            <div class="input composer">
                <textarea name="body" placeholder="Write a comment...">{{ old('body') }}</textarea>
                <div class="toolbar composer__toolbar">
                    <div class="toolbar toolbar--compact">
                        <button class="btn btn--transparent btn--icon btn--small" type="button">
                            <x-waterhole::icon icon="go-bold-16"/>
                        </button>
                        <button class="btn btn--transparent btn--icon btn--small" type="button">
                            <x-waterhole::icon icon="go-italic-16"/>
                        </button>
                    </div>
                    <div class="spacer"></div>
                    <button type="submit" class="btn btn--primary">Post</button>
                </div>
            </div>
        </x-waterhole::field>
    </form>
@else
{{--    <a href="{{ route('waterhole.login') }}">Log in to reply</a>--}}
@endcan

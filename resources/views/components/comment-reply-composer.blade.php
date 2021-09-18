@props(['post', 'parent' => null])

@can('reply', $post)
    <form
        action="{{ route('waterhole.posts.comments.store', ['post' => $post]) }}"
        method="POST"
        id="reply"
    >
        @csrf

        @if ($parent)
            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
{{--            <p>In reply to {{ $parent->user->name }}</p>--}}
        @endif

        <x-waterhole::errors :errors="$errors"/>

        <div class="input composer">
            <textarea name="body" placeholder="Write a comment...">{{ old('body') }}</textarea>
            <div class="toolbar composer__toolbar">
                <div class="spacer"></div>
                <button type="submit" class="btn btn--primary">Post</button>
            </div>
        </div>
    </form>
@else
    <a href="{{ route('waterhole.login') }}">Log in to reply</a>
@endcan

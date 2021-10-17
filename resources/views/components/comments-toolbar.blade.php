@props(['post', 'comments', 'sorts', 'currentSort'])

<div class=""
    style="margin: 0 0 var(--space-xxxl) 0;   background: var(--color-bg); box-shadow: var(--shadow-sm); z-index: 1;  ">
    <div class="container">
       <div class="post-comments__toolbar toolbar" style=" height: 3em">
           <h2 class="h3 post-comments__title">
            {{ __('waterhole::forum.post-comment-count', ['count' => $post->comment_count]) }}
        </h2>

{{--        @if ($post->comment_count > 1)--}}
{{--            <div class="tabs scrollable">--}}
{{--                @foreach ($sorts as $sort)--}}
{{--                    <a--}}
{{--                        href="{{ $post->url }}?sort={{ $sort->handle() }}#comments"--}}
{{--                        class="tab"--}}
{{--                        title="{{ $sort->description() }}"--}}
{{--                        @if ($currentSort === $sort) aria-current="page" @endif--}}
{{--                    >{{ $sort->name() }}</a>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        @endif--}}

    <div class="spacer"></div>

    {{ $comments->fragment('comments')->links() }}
{{--        <div class="spacer"></div>--}}

{{--    @auth--}}
{{--        @if ($post->comment_count)--}}

{{--            <a href="{{ $comments->fragment('')->url($comments->lastPage()) }}#reply" class="btn btn--small">--}}
{{--                <span>Reply</span>--}}
{{--            </a>--}}
{{--        @endif--}}
{{--    @endauth--}}
       </div></div>
</div>

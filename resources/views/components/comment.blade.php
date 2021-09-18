@props(['comment', 'depth' => 0, 'withComposer' => false])

<article class="comment {{ Waterhole\Extend\CommentClasses::getClasses($comment) }}" id="comment-{{ $comment->id }}">
    <header class="comment__header">
        <x-waterhole::attribution :user="$comment->user" :date="$comment->created_at"/>

        @if ($comment->parent && ! $depth)
            <div>
                <a href="{{ $comment->parent->url }}#comment-{{ $comment->parent->id }}" class="comment__parent">
                    <x-heroicon-s-reply class="icon rotate-180"/>
                    <span class="user-label">
                        <span>In reply to</span>
                        <x-waterhole::avatar :user="$comment->parent->user"/>
                        <span>{{ $comment->parent->user->name }}</span>
                    </span>
                </a>
            </div>
        @endif
    </header>

    <div class="comment__body content">
        {{ $comment->body_html }}
    </div>

    <footer class="toolbar comment__footer">
        @components(Waterhole\Extend\CommentFooter::getComponents(), compact('comment'))
        <x-waterhole::action-menu :for="$comment" :button-attributes="['class' => 'btn--small']"/>
    </footer>

    @if ($comment->relationLoaded('replies'))
        <div class="comment__replies">
            @foreach ($comment->replies as $child)
                <x-waterhole::comment
                    :comment="$child"
                    :depth="$depth + 1"
                />
            @endforeach

            @if ($withComposer)
                <div class="post-comments__reply comment" id="reply">
                    <div class="attribution">
                        <x-waterhole::avatar :user="Auth::user()"/>
                    </div>

                    <x-waterhole::comment-reply-composer :post="$comment->post" :parent="$comment"/>
                </div>
            @endif
        </div>
    @elseif ($comment->reply_count)
{{--        <div>--}}
{{--        <a href="{{ $comment->url }}" class="comment__view-replies btn btn--link btn--small">--}}
{{--            View {{ $comment->reply_count }} replies--}}
{{--        </a>--}}
{{--        </div>--}}
    @endif
</article>

@props(['comment', 'depth' => 0])

<article class="comment {{ Waterhole\Extend\CommentClasses::getClasses($comment) }}" id="comment-{{ $comment->id }}">
    <header class="comment__header">
        <x-waterhole::attribution :user="$comment->user"/>

        @if ($comment->parent && ! $depth)
            <div>
                <a href="{{ $comment->parent->url }}" class="comment__parent">
                    <x-heroicon-s-reply class="icon rotate-180"/>
                    <span class="user-label">
                        <span>In reply to</span>
                        <x-waterhole::ui.avatar :user="$comment->parent->user"/>
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
        <x-waterhole::actions.menu :for="$comment" :button-attributes="['class' => 'btn--small']"/>
    </footer>

    @if ($comment->relationLoaded('replies'))
        <div class="comment__replies">
            @foreach ($comment->replies as $child)
                <x-waterhole::comments.comment
                    :comment="$child"
                    :depth="$depth + 1"
                />
            @endforeach
        </div>
    @endif
</article>

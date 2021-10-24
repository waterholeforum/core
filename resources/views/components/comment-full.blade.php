<turbo-frame
    id="@domid($comment)"
    class="comment {{ Waterhole\Extend\CommentClasses::getClasses($comment) }} @if ($withReplies) comment--with-replies @endif"
    role="article"
    tabindex="-1"
    {{ $attributes }}
    data-id="{{ $comment->id }}"
    data-controller="comment"
    data-action="turbo:frame-render->comment#connect"
>
{{--    <span--}}
{{--        class="comment__line"--}}
{{--        data-comment-target="line"--}}
{{--        data-action="click->comment#toggle"--}}
{{--    ></span>--}}

    <div class="comment__main">
        <header class="comment__header">
            <x-waterhole::attribution :user="$comment->user" :date="$comment->created_at"/>

            @if ($comment->parent)
                <div>
                    <a href="{{ $comment->parent->url }}#comment-{{ $comment->parent->id }}" class="comment__parent with-icon" data-turbo-frame="_top">
                        <x-waterhole::icon icon="heroicon-o-reply" class="rotate-180"/>
                        <span>In reply to</span>
                        <span class="user-label">
                            <x-waterhole::avatar :user="$comment->parent->user"/>
                            <span>{{ $comment->parent->user?->name ?: 'Anonymous' }}</span>
                        </span>
                    </a>
                </div>
            @endif
        </header>

        <div class="comment__body content">
            {{ emojify($comment->body_html) }}
        </div>

        <footer class="comment__footer toolbar">
            @components(Waterhole\Extend\CommentFooter::getComponents(), compact('comment', 'withReplies'))

            <x-waterhole::action-menu
                :for="$comment"
                :button-attributes="['class' => 'btn--small']"
                placement="bottom-end"
                class="comment__control"
            />
        </footer>
    </div>

    <turbo-frame id="@domid($comment, 'replies')" @unless ($withReplies) hidden @endunless>
        @if ($withReplies && count($comment->children))
            <ol role="list" tabindex="-1" class="comment__replies">
                @foreach ($comment->children as $child)
                    <li>
                        <x-waterhole::comment-full :comment="$child"/>
                    </li>
                @endforeach
            </ol>
        @else
            <div class="loading-indicator"></div>
        @endif
    </turbo-frame>
</turbo-frame>

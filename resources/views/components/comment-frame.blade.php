<turbo-frame id="@domid($comment)" @if ($lazy) src="{{ $comment->url }}" @endif {{ $attributes }}>
    @unless ($lazy)
        <x-waterhole::comment-full :comment="$comment" :with-replies="$withReplies"/>
    @endunless
</turbo-frame>

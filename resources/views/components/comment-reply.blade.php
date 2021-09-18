@props(['comment'])

<a href="{{ $comment->url }}#reply" class="btn btn--transparent btn--small">
    <x-heroicon-o-chat class="icon"/>
    <span>Reply</span>
</a>

@props(['comment'])

@auth
    <a href="{{ $comment->url }}#@domid($comment)-reply" class="btn btn--small btn--transparent comment__control" data-turbo-frame="_top">
        <x-waterhole::icon icon="heroicon-o-reply"/>
        <span>Reply</span>
    </a>
@endauth

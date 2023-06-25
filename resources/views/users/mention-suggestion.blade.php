<div class="grow row gap-md justify-between">
    <x-waterhole::user-label :$user />

    @if ($user->comment_id)
        <span class="with-icon color-muted text-xxs">
            @icon('tabler-share-3', ['class' => 'flip-horizontal'])
            {{ __('waterhole::forum.comment-reply-button') }}
        </span>
    @endif
</div>

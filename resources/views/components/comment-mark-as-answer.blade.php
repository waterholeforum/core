@if (!$comment->post->answer_id || $comment->isAnswer())
    <x-waterhole::action-button
        :for="$comment"
        :action="Waterhole\Actions\MarkAsAnswer::class"
        class="btn btn--sm btn--transparent"
        data-turbo-frame="_top"
    />
@endif

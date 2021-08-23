<span>
    @if ($post->lastComment)
        {{ __('waterhole::forum.post-activity-replied', [
            'userName' => $post->lastComment->user->name,
            'date' => $post->last_comment_at,
        ]) }}
    @elseif ($post->user)
        {{ __('waterhole::forum.post-activity-posted', [
            'userName' => $post->user->name,
            'date' => $post->created_at,
        ]) }}
    @endif
</span>

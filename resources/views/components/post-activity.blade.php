@if ($post->lastComment)
    <span>{{ __('waterhole::forum.post-activity-replied', [
        'userName' => $post->lastComment->user?->name ?? 'Anonymous',
        'date' => $post->last_comment_at,
    ]) }}</span>
@elseif ($post->user)
    <span>{{ __('waterhole::forum.post-activity-posted', [
        'userName' => $post->user->name,
        'date' => $post->created_at,
    ]) }}</span>
@endif

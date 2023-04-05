<a
    href="{{ $post->url }}#comments"
    class="btn btn--sm btn--outline @if (!$post->comment_count) is-disabled @endif"
    data-action="post#appearAsRead"
>
    @icon('tabler-message-circle-2')
    <span>{{ Waterhole\compact_number($post->comment_count) }}</span>
    <ui-tooltip>
        {{ __('waterhole::forum.post-comments-link', ['count' => $post->comment_count]) }}
    </ui-tooltip>
</a>

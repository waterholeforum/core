@foreach ($items as $item)
    <turbo-stream target="@domid($item)" action="remove"></turbo-stream>

    <turbo-stream targets="#@domid($item->post) .post-comments__title" action="update">
        <template>
            {{ __('waterhole::forum.post-comment-count', ['count' => $item->post->comment_count]) }}
        </template>
    </turbo-stream>

    <turbo-stream targets="#@domid($item->post) .comment-count" action="update">
        <template>
            {{ $item->post->comment_count }}
        </template>
    </turbo-stream>
@endforeach

<turbo-stream target="modal" action="append">
    <template>
        <stimulus-invoke action="modal#hide"></stimulus-invoke>
    </template>
</turbo-stream>

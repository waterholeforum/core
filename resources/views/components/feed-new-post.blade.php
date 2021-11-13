<a
    href="{{ route('waterhole.posts.create').($channel ? '?'.Arr::query(['channel' => $channel->id]) : '') }}"
    class="btn btn--primary"
>
    <span>New Post</span>
</a>

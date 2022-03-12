<a
    href="{{ route('waterhole.posts.create').($channel ? '?'.Arr::query(['channel' => $channel->id]) : '') }}"
    class="btn bg-accent"
>
    <span>New Post</span>
</a>

<x-waterhole::user-profile
    :user="$user"
    :title="__('waterhole::user.user-'.$comments->currentFilter()->handle().'-comments-title', ['userName' => $user->name])"
>
    <div class="stack gap-lg">
        <div class="row gap-xs wrap">
            <x-waterhole::feed-sort :feed="$comments"/>
            <x-waterhole::feed-top-period :feed="$comments"/>
        </div>

        <x-waterhole::feed2 :feed="$comments">
            <ul role="list" class="stack gap-lg">
                @foreach ($component->items as $comment)
                    <li class="stack gap-xs card comment-card">
                        <ol class="color-muted text-xs comment-card__post breadcrumb">
                            <li>
                                <x-waterhole::channel-label :channel="$comment->post->channel"/>
                            </li>
                            <li>
                                <a
                                    href="{{ $comment->post_url }}"
                                    class="weight-medium"
                                >{{ $comment->post->title }}</a>
                            </li>
                        </ol>
                        <x-waterhole::comment-full :comment="$comment"/>
                    </li>
                @endforeach
            </ul>

            <x-slot name="empty">
                <div class="placeholder">
                    <x-waterhole::icon
                        icon="heroicon-o-chat-alt-2"
                        class="placeholder__visual"
                    />
                    <h3>
                        {{ __('waterhole::user.comments-empty-message') }}
                    </h3>
                </div>
            </x-slot>
        </x-waterhole::feed2>
    </div>
</x-waterhole::user-profile>

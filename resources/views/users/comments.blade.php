<x-waterhole::user-profile :user="$user" :title="$user->name.'\'s '.$comments->currentFilter()->label().' Comments'">
    <div class="stack-lg">
        <div class="toolbar">
            <x-waterhole::feed-sort :feed="$comments"/>
            <x-waterhole::feed-top-period :feed="$comments"/>
        </div>

        <x-waterhole::feed2 :feed="$comments">
            @foreach ($component->items as $comment)
                <div class="stack-xs card comment-card" style="margin-bottom: var(--space-lg)">
                    <div class="color-muted text-xs comment-card__post">
                        <x-waterhole::channel-label :channel="$comment->post->channel"/> ›
                        <a href="{{ $comment->post_url }}" style="font-weight: var(--font-weight-medium)">
                            {{ $comment->post->title }}
                        </a>
                    </div>
                    <x-waterhole::comment-full :comment="$comment"/>
                </div>
            @endforeach

            <x-slot name="empty">
                <div class="placeholder">
                    <x-waterhole::icon icon="heroicon-o-chat-alt-2" class="placeholder__visual"/>
                    <h3>No Comments</h3>
                </div>
            </x-slot>
        </x-waterhole::feed2>
    </div>
</x-waterhole::user-profile>

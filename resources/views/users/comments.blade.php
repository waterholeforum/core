<x-waterhole::user-profile
    :user="$user"
    :title="__('waterhole::user.user-'.$comments->currentFilter()->handle().'-comments-title', ['userName' => $user->name])"
>
    <div class="stack gap-lg">
        <div class="row gap-xs wrap">
            <x-waterhole::feed-filters :feed="$comments"/>
            <x-waterhole::feed-top-period :feed="$comments"/>
        </div>

        @php
            $items = $comments->items();
        @endphp

        @if ($items->isNotEmpty())
            <x-waterhole::infinite-scroll :paginator="$items">
                <ul role="list" class="card-list">
                    @foreach ($items as $comment)
                        <li class="card comment-card">
                            <ol class="color-muted text-xs card__header breadcrumb">
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
                            <div class="card__body">
                                <x-waterhole::comment-full :comment="$comment"/>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-waterhole::infinite-scroll>
        @else
            <div class="placeholder">
                <x-waterhole::icon
                    icon="tabler-messages"
                    class="placeholder__visual"
                />
                <p class="h4">
                    {{ __('waterhole::user.comments-empty-message') }}
                </p>
            </div>
        @endif
    </div>
</x-waterhole::user-profile>

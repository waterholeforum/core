@php
    $title = __('waterhole::user.user-comments-title', Waterhole\user_variables($user));
@endphp

<x-waterhole::user-profile :user="$user" :title="$title">
    <h2 class="visually-hidden">{{ $title }}</h2>

    <div class="stack gap-lg">
        <div class="row gap-xs wrap">
            <x-waterhole::feed-filters :feed="$comments" />
            <x-waterhole::feed-top-period :feed="$comments" />
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
                                    <x-waterhole::channel-label
                                        :channel="$comment->post->channel"
                                    />
                                </li>
                                <li>
                                    <a href="{{ $comment->post_url }}" class="weight-medium">
                                        {{ $comment->post->title }}
                                    </a>
                                </li>
                            </ol>
                            <x-waterhole::comment-full :comment="$comment" truncate />
                        </li>
                    @endforeach
                </ul>
            </x-waterhole::infinite-scroll>
        @else
            <div class="placeholder">
                @icon('tabler-messages', ['class' => 'placeholder__icon'])
                <p class="h4">
                    {{ __('waterhole::user.comments-empty-message') }}
                </p>
            </div>
        @endif
    </div>
</x-waterhole::user-profile>

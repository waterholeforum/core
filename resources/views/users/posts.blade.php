@php
    $title = __('waterhole::user.user-posts-title', Waterhole\user_variables($user));
@endphp

<x-waterhole::user-profile :user="$user" :title="$title">
    <h2 class="visually-hidden">{{ $title }}</h2>

    <div class="stack gap-lg">
        <div class="row gap-xs wrap">
            <x-waterhole::feed-filters :feed="$posts"/>
            <x-waterhole::feed-top-period :feed="$posts"/>
            <div class="grow"></div>
            <x-waterhole::post-feed-controls :feed="$posts"/>
        </div>

        @php
            $items = $posts->items();
        @endphp

        @if ($items->isNotEmpty())
            <x-waterhole::infinite-scroll :paginator="$items">
                <div class="post-{{ $posts->currentLayout() }}">
                    @foreach ($items as $post)
                        <x-dynamic-component
                            :component="'waterhole::post-'.$posts->currentLayout().'-item'"
                            :post="$post"
                        />
                    @endforeach
                </div>
            </x-waterhole::infinite-scroll>
        @else
            <div class="placeholder">
                @icon('tabler-messages', ['class' => 'placeholder__icon'])
                <p class="h4">
                    {{ __('waterhole::user.posts-empty-message') }}
                </p>
            </div>
        @endif
    </div>
</x-waterhole::user-profile>

<x-waterhole::user-profile
    :user="$user"
    :title="__('waterhole::user.user-'.$posts->currentFilter()->handle().'-posts-title', ['userName' => $user->name])"
>
    <div class="stack gap-lg">
        <div class="row gap-xs wrap">
            <x-waterhole::feed-sort :feed="$posts"/>
            <x-waterhole::feed-top-period :feed="$posts"/>
            <div class="grow"></div>
            <x-waterhole::feed-controls :feed="$posts"/>
        </div>

        <x-waterhole::feed2 :feed="$posts" class="post-feed">
            <div class="post-{{ $posts->currentLayout() }}">
                @foreach ($component->items as $post)
                    <x-dynamic-component
                        :component="'waterhole::post-'.$posts->currentLayout().'-item'"
                        :post="$post"
                    />
                @endforeach
            </div>

            <x-slot name="empty">
                <div class="placeholder">
                    <x-waterhole::icon
                        icon="heroicon-o-chat-alt-2"
                        class="placeholder__visual"
                    />
                    <h3>
                        {{ __('waterhole::user.posts-empty-message') }}
                    </h3>
                </div>
            </x-slot>
        </x-waterhole::feed2>
    </div>
</x-waterhole::user-profile>

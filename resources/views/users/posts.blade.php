<x-waterhole::user-profile
    :user="$user"
    :title="$user->name.'\'s '.$posts->currentFilter()->label().' Posts'"
>
    <div class="stack gap-lg">
        <div class="row gap-xs wrap">
            <x-waterhole::feed-sort :feed="$posts"/>
            <x-waterhole::feed-top-period :feed="$posts"/>
            <x-waterhole::feed-controls :feed="$posts"/>
        </div>

        <x-waterhole::feed2 :feed="$posts" class="post-feed">
            @foreach ($component->items as $post)
                <x-dynamic-component
                    :component="'waterhole::post-'.$posts->currentLayout().'-item'"
                    :post="$post"
                />
            @endforeach

            <x-slot name="empty">
                <div class="placeholder">
                    <x-waterhole::icon icon="heroicon-o-chat-alt-2" class="placeholder__visual"/>
                    <h3>No Posts</h3>
                </div>
            </x-slot>
        </x-waterhole::feed2>
    </div>
</x-waterhole::user-profile>

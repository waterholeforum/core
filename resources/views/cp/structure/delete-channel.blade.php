<div class="stack gap-lg" data-controller="reveal">
    <h1 class="h4">
        {{ __('waterhole::cp.delete-channel-title') }}
        <x-waterhole::channel-label :channel="$channel"/>
    </h1>

    @if ($postCount > 0)
        <div class="stack gap-sm">
            <label class="choice">
                <input
                    @checked(!request('move_posts'))
                    data-reveal-target="if"
                    name="move_posts"
                    type="radio"
                    value="0"
                >
                {{ __('waterhole::cp.delete-channel-posts-label', ['count' => $postCount]) }}
            </label>

            <label class="choice">
                <input
                    type="radio"
                    name="move_posts"
                    value="1"
                    @checked(request('move_posts'))
                    data-reveal-target="if"
                >
                {{ __('waterhole::cp.move-to-channel-posts-label', ['count' => $postCount]) }}
            </label>

            <x-waterhole::channel-picker
                name="channel_id"
                :exclude="[$channel->id]"
                :value="request('channel_id')"
                data-reveal-target="then"
                data-reveal-value="1"
            />
        </div>
    @endif
</div>

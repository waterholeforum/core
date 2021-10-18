<div class="card channel-card">
    <x-waterhole::icon :icon="$channel->icon" class="channel-card__icon"/>

    <div class="channel-card__info">
        <h2>{{ $channel->name }}</h2>
        @if ($channel->description)
            <p>{{ $channel->description }}</p>
        @endif
    </div>

    <div class="channel-card__controls">
        @php
            $buttonActions = [
                Waterhole\Actions\Follow::class,
                Waterhole\Actions\Unfollow::class
            ];
        @endphp

        <x-waterhole::action-buttons
            :for="$channel"
            :only="$buttonActions"
            :button-attributes="['class' => 'btn channel-card__follow']"
        />

        <x-waterhole::action-menu
            :for="$channel"
            :exclude="$buttonActions"
            placement="bottom-end"
        />
    </div>
</div>

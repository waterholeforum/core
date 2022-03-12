<div class="card channel-card">
    <x-waterhole::icon :icon="$channel->icon" class="channel-card__icon"/>

    <div class="channel-card__info">
        <h2 class="h3">{{ $channel->name }}</h2>
        @if ($channel->description)
            <p>{{ $channel->description }}</p>
        @endif
    </div>

    <div class="channel-card__controls">
        @php
            $buttonActions = [
                Waterhole\Actions\Follow::class,
                Waterhole\Actions\Unfollow::class,
                Waterhole\Actions\Ignore::class,
                Waterhole\Actions\Unignore::class,
            ];
        @endphp

        <x-waterhole::follow-button
            :followable="$channel"
        />

        <x-waterhole::action-menu
            :for="$channel"
            :exclude="$buttonActions"
            placement="bottom-end"
        />
    </div>
</div>

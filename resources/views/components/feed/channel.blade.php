@isset($channel)
    <div class="card channel-card">
        <x-waterhole::ui.icon :icon="$channel->icon" class="channel-card__icon"/>

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

            <x-waterhole::actions.buttons
                :for="$channel"
                :only="$buttonActions"
                class="btn"
            />

            <x-waterhole::actions.menu
                :for="$channel"
                :except="$buttonActions"
            />
        </div>
    </div>
@endisset

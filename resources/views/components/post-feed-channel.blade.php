<div class="card card__body channel-card">
    <div class="channel-card__controls row gap-xs justify-end">
        <x-waterhole::follow-button :followable="$channel"/>

        <x-waterhole::action-menu
            placement="bottom-end"
            :for="$channel"
            :exclude="[
                Waterhole\Actions\Follow::class,
                Waterhole\Actions\Unfollow::class,
                Waterhole\Actions\Ignore::class,
                Waterhole\Actions\Unignore::class,
            ]"
        />
    </div>

    <div class="row wrap gap-md align-start">
        <x-waterhole::icon :icon="$channel->icon" class="channel-card__icon"/>

        <div class="channel-card__info stack gap-md">
            <h2 class="h3">{{ $channel->name }}</h2>

            @if ($channel->description)
                <p class="content">{{ $channel->description }}</p>
            @endif
        </div>
    </div>
</div>

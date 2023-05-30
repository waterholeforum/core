<div {{ $attributes->class('channel-card card card__body row align-start gap-md') }}>
    @icon($channel->icon, ['class' => 'channel-card__icon text-xxl'])

    <div class="channel-card__inner grow row wrap gap-md">
        <div class="channel-card__info grow stack gap-xs">
            <h2 class="h3">{{ $channel->name }}</h2>

            @if ($description = $channel->description_html)
                <div class="content measure">{{ $description }}</div>
            @endif
        </div>

        <div class="channel-card__controls row gap-xs justify-end">
            <x-waterhole::follow-button :followable="$channel"/>

            <x-waterhole::action-menu
                placement="bottom-end"
                :for="$channel"
                :exclude="[
                    Waterhole\Actions\Follow::class,
                ]"
            />
        </div>
    </div>
</div>

<div {{ $attributes }} class="field">
    @if ($value)
        <input type="hidden" name="channel_id" value="{{ $value }}">
    @endif

    <ui-popup placement="bottom-start">
        <button class="btn" type="button">
            @if ($selectedChannel)
                <x-waterhole::channel-label :channel="$selectedChannel"/>
            @else
                <span>{{ __('waterhole::forum.channel-picker-placeholder') }}</span>
            @endif
            <x-waterhole::icon icon="tabler-chevron-down"/>
        </button>

        <ui-menu class="menu channel-picker__menu" hidden>
            @foreach ($items as $item)
                @if ($item instanceof Waterhole\Models\StructureHeading)
                    <h4 class="menu-heading">{{ $item->name }}</h4>
                @elseif ($item instanceof Waterhole\Models\Channel)
                    <x-waterhole::menu-item
                        type="submit"
                        :name="$name"
                        :value="$item->id"
                        :active="$item->id == $value"
                        role="menuitemradio"
                        :label="$item->name"
                        :description="$item->description"
                        :icon="$item->icon"
                    />
                @endif
            @endforeach
        </ui-menu>
    </ui-popup>

    @if ($instructions = $selectedChannel?->instructions_html)
        <div class="rounded p-lg bg-warning-soft content">
            {{ Waterhole\emojify($instructions) }}
        </div>
    @endif
</div>

<ui-popup {{ $attributes->merge(['placement' => 'bottom-start']) }}>
    <button type="button" class="{{ $buttonClass }} {{ $followable->isFollowed() ? 'bg-attention-light color-attention' : '' }}">
        @if ($followable->isFollowed())
            <x-waterhole::icon icon="heroicon-o-bell"/>
            <span>{{ __('waterhole::forum.follow-button-following') }}</span>
        @elseif ($followable->isIgnored())
            <x-waterhole::icon icon="heroicon-o-volume-off"/>
            <span>{{ __('waterhole::forum.follow-button-ignored') }}</span>
        @else
            <x-waterhole::icon icon="heroicon-o-bell"/>
            <span>{{ __('waterhole::forum.follow-button') }}</span>
        @endif
        <x-waterhole::icon icon="heroicon-s-chevron-down"/>
    </button>

    <ui-menu class="menu" hidden>
        <x-waterhole::action-form :for="$followable">
            <x-waterhole::menu-item
                tag="button"
                name="action_class"
                :value="Waterhole\Actions\Unfollow::class"
                :active="!$followable->isFollowed() && !$followable->isIgnored()"
                icon="heroicon-o-at-symbol"
                :label="__($localePrefix.'-default-notifications-title')"
                :description="__($localePrefix.'-default-notifications-description')"
            />

            <x-waterhole::menu-item
                tag="button"
                name="action_class"
                :value="Waterhole\Actions\Follow::class"
                :active="$followable->isFollowed()"
                icon="heroicon-o-bell"
                :label="__($localePrefix.'-follow-title')"
                :description="__($localePrefix.'-follow-description')"
            />

            <x-waterhole::menu-item
                tag="button"
                name="action_class"
                :value="Waterhole\Actions\Ignore::class"
                :active="$followable->isIgnored()"
                icon="heroicon-o-volume-off"
                :label="__($localePrefix.'-ignore-title')"
                :description="__($localePrefix.'-ignore-description')"
            />
        </x-waterhole::action-form>
    </ui-menu>
</ui-popup>

<ui-popup {{ $attributes->merge(['placement' => 'bottom-start']) }}>
    <button type="button" class="{{ $buttonClass }} {{ $followable->isFollowed() ? 'bg-warning-soft color-warning' : '' }}">
        @if ($followable->isFollowed())
            <x-waterhole::icon icon="tabler-bell"/>
            <span>{{ __('waterhole::forum.follow-button-following') }}</span>
        @elseif ($followable->isIgnored())
            <x-waterhole::icon icon="tabler-volume-3"/>
            <span>{{ __('waterhole::forum.follow-button-ignored') }}</span>
        @else
            <x-waterhole::icon icon="tabler-bell"/>
            <span>{{ __('waterhole::forum.follow-button') }}</span>
        @endif
        <x-waterhole::icon icon="tabler-chevron-down"/>
    </button>

    <ui-menu class="menu" hidden>
        <x-waterhole::action-form :for="$followable">
            <x-waterhole::menu-item
                tag="button"
                name="action_class"
                :value="Waterhole\Actions\Unfollow::class"
                :active="!$followable->isFollowed() && !$followable->isIgnored()"
                icon="tabler-at"
                :label="__($localePrefix.'-default-notifications-title')"
                :description="__($localePrefix.'-default-notifications-description')"
            />

            <x-waterhole::menu-item
                tag="button"
                name="action_class"
                :value="Waterhole\Actions\Follow::class"
                :active="$followable->isFollowed()"
                icon="tabler-bell"
                :label="__($localePrefix.'-follow-title')"
                :description="__($localePrefix.'-follow-description')"
            />

            <x-waterhole::menu-item
                tag="button"
                name="action_class"
                :value="Waterhole\Actions\Ignore::class"
                :active="$followable->isIgnored()"
                icon="tabler-volume-3"
                :label="__($localePrefix.'-ignore-title')"
                :description="__($localePrefix.'-ignore-description')"
            />
        </x-waterhole::action-form>
    </ui-menu>
</ui-popup>

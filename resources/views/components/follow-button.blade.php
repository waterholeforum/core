<ui-popup {{ $attributes->merge(['placement' => 'bottom-start']) }}>
    <button type="button" class="{{ $buttonClass }}">
        @if ($followable->isFollowed())
            <x-waterhole::icon icon="heroicon-o-check"/>
            <span>Following</span>
        @elseif ($followable->isIgnored())
            <x-waterhole::icon icon="heroicon-o-volume-off"/>
            <span>Ignored</span>
        @else
            <x-waterhole::icon icon="heroicon-o-bell"/>
            <span>Follow</span>
        @endif
        <x-waterhole::icon icon="heroicon-s-chevron-down"/>
    </button>

    <ui-menu class="menu" hidden>
        <x-waterhole::action-form :for="$followable">
            <button
                class="menu-item"
                role="menuitemradio"
                type="submit"
                name="action_class"
                value="{{ Waterhole\Actions\Unfollow::class }}"
                @if (! $followable->isFollowed() && ! $followable->isIgnored())
                    aria-checked="true"
                @endif
            >
                <x-waterhole::icon icon="heroicon-o-at-symbol"/>
                <span>
                    <span class="menu-item__title">Default</span>
                    <span class="menu-item__description">Receive notifications when you're mentioned.</span>
                </span>
                @if (! $followable->isFollowed() && ! $followable->isIgnored())
                    <x-waterhole::icon
                        icon="heroicon-o-check"
                        class="menu-item__check"
                    />
                @endif
            </button>

            <button
                class="menu-item"
                role="menuitemradio"
                type="submit"
                name="action_class"
                value="{{ Waterhole\Actions\Follow::class }}"
                @if ($followable->isFollowed())
                    aria-checked="true"
                @endif
            >
                <x-waterhole::icon icon="heroicon-o-bell"/>
                <span>
                    <span class="menu-item__title">Following</span>
                    <span class="menu-item__description">Receive notifications when there are new posts in this channel.</span>
                </span>
                @if ($followable->isFollowed())
                    <x-waterhole::icon
                        icon="heroicon-o-check"
                        class="menu-item__check"
                    />
                @endif
            </button>

            <button
                class="menu-item"
                role="menuitemradio"
                type="submit"
                name="action_class"
                value="{{ Waterhole\Actions\Ignore::class }}"
                @if ($followable->isIgnored())
                    aria-checked="true"
                @endif
            >
                <x-waterhole::icon icon="heroicon-o-volume-off"/>
                <span>
                    <span class="menu-item__title">Ignore</span>
                    <span class="menu-item__description">Never be notified about activity in this channel, and hide posts from Home.</span>
                </span>
                @if ($followable->isIgnored())
                    <x-waterhole::icon
                        icon="heroicon-o-check"
                        class="menu-item__check"
                    />
                @endif
            </button>
        </x-waterhole::action-form>
    </ui-menu>
</ui-popup>

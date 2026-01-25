<div class="attribution">
    <span class="attribution__user">
        <x-waterhole::user-link :user="$user" class="attribution__link">
            <x-waterhole::avatar :user="$user" />

            @if ($user?->isOnline())
                <span class="dot color-success">
                    <ui-tooltip>{{ __('waterhole::user.online-label') }}</ui-tooltip>
                </span>
            @endif

            <span class="attribution__name">{{ Waterhole\username($user) }}</span>
        </x-waterhole::user-link>

        <x-waterhole::user-groups :user="$user" />
    </span>

    <span class="attribution__info">
        @if ($user?->headline)
            <span>{{ $user->headline }}</span>
        @endif

        @if ($displayDate = $editDate ?: $date)
            <span>
                {{-- format-ignore-start --}}
                @if ($permalink)
                    <a
                        href="{{ $permalink }}"
                        class="color-inherit with-icon"
                        target="_top"
                    >
                @else
                    <span class="with-icon">
                @endif
                    @if ($editDate)
                        @icon('tabler-pencil', ['class' => 'icon--narrow text-xxs'])
                    @endif

                    <x-waterhole::relative-time :datetime="$displayDate" title="" />

                    <ui-tooltip placement="bottom" tooltip-class="tooltip tooltip--block">
                        @if ($date)
                            <div>
                                <small>{{ __('waterhole::forum.attribution-timestamp-created-label') }}</small>
                                {{ $date->toDayDateTimeString() }}
                            </div>
                        @endif
                        @if ($editDate)
                            <div>
                                <small>{{ __('waterhole::forum.attribution-timestamp-edited-label') }}</small>
                                {{ $editDate->toDayDateTimeString() }}
                            </div>
                        @endif
                    </ui-tooltip>
                @if ($permalink)
                    </a>
                @else
                    </span>
                @endif
                {{-- format-ignore-end --}}
            </span>
        @endif
    </span>
</div>

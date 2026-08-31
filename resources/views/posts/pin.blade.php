<div class="stack gap-lg">
    <h1 class="h3">{{ __('waterhole::forum.pin-post-title') }}</h1>

    <div class="stack gap-md">
        <label class="choice align-start">
            <input
                type="radio"
                name="pinned_scope"
                value="channel"
                @checked(old('pinned_scope', 'channel') === 'channel')
            />
            <span class="row gap-xxs">
                {{ __('waterhole::forum.pin-in-channel-prefix') }}
                <x-waterhole::channel-label :$channel />
            </span>
        </label>

        <label class="choice align-start">
            <input
                type="radio"
                name="pinned_scope"
                value="global"
                @checked(old('pinned_scope') === 'global')
            />
            <span class="stack gap-xxs">
                <span>
                    {{ __('waterhole::forum.pin-globally-label') }}
                </span>
                <small class="field__description">
                    {{ __('waterhole::forum.pin-globally-description') }}
                </small>
            </span>
        </label>
    </div>
</div>

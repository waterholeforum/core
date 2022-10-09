@section('avatar')
    <div class="field" role="group">
        <div class="field__label">{{ __('waterhole::user.avatar-label') }}</div>
        <div class="row gap-md">
            <x-waterhole::avatar :user="$user" style="width: 10ch"/>
            <div class="stack gap-md">
                <input
                    type="file"
                    class="input"
                    name="avatar"
                    accept="image/*,.jpg,.png,.gif,.bmp"
                    capture="user"
                >
                @if ($user?->avatar)
                    <label class="choice">
                        <input type="checkbox" name="remove_avatar" value="1">
                        {{ __('waterhole::user.remove-avatar-label') }}
                    </label>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('headline')
    <x-waterhole::field
        name="headline"
        :label="__('waterhole::user.headline-label')"
    >
        <div class="stack gap-xs">
            <input
                id="{{ $component->id }}"
                type="text"
                name="headline"
                value="{{ old('headline', $user?->headline) }}"
                class="input block"
                maxlength="30"
            >
            <p class="field__description">
                {{ __('waterhole::user.headline-description') }}
            </p>
        </div>
    </x-waterhole::field>
@endsection

@section('bio')
    <x-waterhole::field name="bio" :label="__('waterhole::user.bio-label')">
        <div class="stack gap-xs">
            <textarea
                id="{{ $component->id }}"
                type="text"
                name="bio"
                class="input block"
                maxlength="255"
            >{{ old('bio', $user?->bio) }}</textarea>
            <p class="field__description">
                {{ __('waterhole::user.bio-description') }}
            </p>
        </div>
    </x-waterhole::field>
@endsection

@section('location')
    <x-waterhole::field
        name="location"
        :label="__('waterhole::user.location-label')"
    >
        <input
            id="{{ $component->id }}"
            type="text"
            name="location"
            value="{{ old('location', $user?->location) }}"
            class="input block"
            maxlength="30"
        >
    </x-waterhole::field>
@endsection

@section('website')
    <x-waterhole::field name="website" :label="__('waterhole::user.website-label')">
        <input
            id="{{ $component->id }}"
            type="text"
            name="website"
            value="{{ old('website', $user?->website) }}"
            class="input block"
            maxlength="100"
        >
    </x-waterhole::field>
@endsection

@section('privacy')
    <div role="group" class="field">
        <div class="field__label">{{ __('waterhole::user.privacy-title') }}</div>
        <div>
            <input type="hidden" name="show_online" value="0">
            <label for="show_online" class="choice">
                <input
                    id="show_online"
                    type="checkbox"
                    name="show_online"
                    value="1"
                    @checked($user?->show_online)
                >
                {{ __('waterhole::user.show-online-label') }}
            </label>
        </div>
    </div>
@endsection

@components(Waterhole\Extend\UserProfileForm::build(), compact('user'))

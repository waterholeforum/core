<div role="group">
    <div class="field__label">Avatar</div>
    <div class="row gap-xs" style="gap:var(--space-md)">
        <x-waterhole::avatar :user="$user" style="width: 10ch"/>
        <div class="stack gap-md">
            <input type="file" class="input" name="avatar" accept="image/*,.jpg,.png,.gif,.bmp" capture="user">
            @if ($user?->avatar)
                <label class="choice">
                    <input type="checkbox" name="remove_avatar" value="1">
                    Remove avatar
                </label>
            @endif
        </div>
    </div>
</div>

<x-waterhole::field name="headline" label="Headline">
    <div class="stack gap-xs">
        <input id="{{ $component->id }}" type="text" name="headline" value="{{ old('headline', $user?->headline) }}" class="input block" maxlength="30">
        <p class="field__description">Describe yourself in a few words. This will be displayed next to your name.</p>
    </div>
</x-waterhole::field>

<x-waterhole::field name="bio" label="Bio">
    <div class="stack gap-xs">
        <textarea id="{{ $component->id }}" type="text" name="bio" class="input block" maxlength="255">{{ old('bio', $user?->bio) }}</textarea>
        <p class="field__description">Write more about yourself. This will be displayed on your profile.</p>
    </div>
</x-waterhole::field>

<x-waterhole::field name="location" label="Location">
    <input id="{{ $component->id }}" type="text" name="location" value="{{ old('location', $user?->location) }}" class="input block" maxlength="30">
</x-waterhole::field>

<x-waterhole::field name="website" label="Website">
    <input id="{{ $component->id }}" type="text" name="website" value="{{ old('website', $user?->website) }}" class="input block" maxlength="100">
</x-waterhole::field>

<div role="group" class="field">
    <div class="field__label">Privacy</div>
    <div>
        <input type="hidden" name="show_online" value="0">
        <label for="show_online" class="choice">
            <input id="show_online" type="checkbox" name="show_online" value="1" @if ($user?->show_online) checked @endif>
            Show when I was last online
        </label>
    </div>
</div>

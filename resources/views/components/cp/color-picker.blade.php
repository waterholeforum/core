<div data-controller="color-picker" class="color-picker">
    <hex-input
        alpha
        color="{{ $value }}"
        class="input-container color-picker__input-container"
        data-action="color-changed->color-picker#colorChanged"
        data-color-picker-target="input"
    >
        <span class="no-pointer">
            <span
                class="color-picker__swatch"
                style="background-color: {{ $value }}"
                data-color-picker-target="swatch"
            ></span>
        </span>

        <input
            type="text"
            name="{{ $name }}"
            value="{{ ltrim($value ?? "", "#") }}"
            @if ($placeholder)
                placeholder="{{ $placeholder }}"
            @endif
            id="{{ $id }}"
            class="color-picker__input"
            maxlength="8"
            pattern="[0-9A-Fa-f]{3}|[0-9A-Fa-f]{4}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8}"
            data-action="focus->color-picker#show blur->color-picker#hide"
        />
    </hex-input>

    <hex-alpha-color-picker
        class="color-picker__picker"
        color="{{ $value ?: "#000000" }}"
        hidden
        data-action="color-changed->color-picker#colorChanged focus->color-picker#show blur->color-picker#hide"
        data-color-picker-target="picker"
    ></hex-alpha-color-picker>
</div>

<div data-controller="color-picker" class="color-picker">
    <hex-input
        alpha
        color="{{ $value }}"
        class="input-container"
        data-action="color-changed->color-picker#colorChanged"
        data-color-picker-target="input"
        class="color-picker__input-container"
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
            value="{{ $value }}"
            id="{{ $id }}"
            class="color-picker__input"
            maxlength="6"
            pattern="[0-9A-Fa-f]{3}|[0-9A-Fa-f]{4}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8}"
            data-action="focus->color-picker#show blur->color-picker#hide"
        />
    </hex-input>

    <hex-alpha-color-picker
        class="color-picker__picker"
        color="{{ $value }}"
        hidden
        data-action="color-changed->color-picker#colorChanged focus->color-picker#show blur->color-picker#hide"
        data-color-picker-target="picker"
    ></hex-alpha-color-picker>
</div>

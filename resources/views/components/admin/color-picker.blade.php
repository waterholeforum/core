<div data-controller="color-picker" class="color-picker">
    <hex-input
        color="#{{ $value }}"
        class="input-container"
        data-action="color-changed->color-picker#colorChanged"
        data-color-picker-target="input"
        class="color-picker__input-container"
    >
        <span class="no-pointer">
            <span
                class="color-picker__swatch"
                style="background-color: #{{ $value }}"
                data-color-picker-target="swatch"
            ></span>
        </span>

        <input
            type="text"
            name="{{ $name }}"
            value="{{ $value }}"
            id="{{ $id }}"
            class="input color-picker__input"
            maxlength="6"
            pattern="[0-9a-f]{3}|[0-9a-f]{6}"
            data-action="focus->color-picker#show blur->color-picker#hide"
        >
    </hex-input>

    <hex-color-picker
        class="color-picker__picker"
        color="#{{ $value }}"
        hidden
        data-action="color-changed->color-picker#colorChanged focus->color-picker#show blur->color-picker#hide"
        data-color-picker-target="picker"
    ></hex-color-picker>
</div>

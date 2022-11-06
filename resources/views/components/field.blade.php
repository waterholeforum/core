<div {{ $attributes->class(['field', 'has-error' => $errors->has($name)]) }}>
    @if ($label)
        <label for="{{ $name }}" class="field__label">{{ $label }}</label>
    @endif

    <div class="grow">
        {{ $slot }}

        @if ($description)
            <p class="field__description">{{ $description }}</p>
        @endif

        @error($name)
            <div class="field__status color-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

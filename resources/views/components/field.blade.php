<div {{ $attributes->class(['field', 'has-error' => $errors->has($name)]) }}>
    @if ($label)
        <label for="{{ $id }}" class="field__label">{{ $label }}</label>
    @endif

    <div class="grow stack gap-xs">
        <div>
            {{ $slot }}
        </div>

        @if ($description)
            <p class="field__description">{{ $description }}</p>
        @endif

        @error($name)
            <div class="field__status color-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

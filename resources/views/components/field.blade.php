@props(['name', 'label', 'description' => null])

<div {{ $attributes->class(['field', 'has-error' => $errors->has($name)]) }}>
    <label for="{{ $name }}" class="field__label">{{ $label }}</label>

    @if ($description)
        <p class="field__description">{{ $description }}</p>
    @endif

    {{ $slot }}

    @error($name)
        <div class="field__status color-danger">{{ $message }}</div>
    @enderror
</div>

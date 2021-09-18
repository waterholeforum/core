@props(['title'])

<div {{ $attributes->class('dialog') }}>
    <header class="dialog__header">
        <h1 class="dialog__title">
            {{ $title }}
        </h1>
    </header>

    <div class="dialog__body">
        {{ $slot }}
    </div>
</div>

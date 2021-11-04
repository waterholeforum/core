<div {{ $attributes->class('dialog') }}>
    <header class="dialog__header">
        <h1 class="dialog__title h3" id="dialog-title">
            {{ $title }}
        </h1>
    </header>

    <div class="dialog__body">
        {{ $slot }}
    </div>
</div>

<div {{ $attributes->class('dialog') }}>
    @if (!empty($title) || !empty($header))
        <header class="dialog__header">
            <h1 class="dialog__title h3" id="dialog-title">
                {{ $title }}
            </h1>

            {{ $header ?? '' }}
        </header>
    @endif

    <div class="dialog__body">
        {{ $slot }}
    </div>
</div>

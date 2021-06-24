<div {{ $attributes->class('dialog') }}>
    @if ($title || isset($header))
        <header class="dialog__header">
            @if ($title)
                <h1 class="dialog__title h3" id="dialog-title">
                    {{ $title }}
                </h1>
            @endif

            {{ $header ?? '' }}
        </header>
    @endif

    <div class="dialog__body">
        {{ $slot }}
    </div>
</div>

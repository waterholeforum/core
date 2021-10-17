@props(['type' => null])

<div {{ $attributes->class(['alert', $type ? "alert--$type" : null]) }}>
    <div class="alert__icon">
        <x-waterhole::icon icon="heroicon-o-exclamation-circle"/>
    </div>
    <div class="alert__message content">
        {{ $slot }}
    </div>
</div>

<div {{ $attributes->class('channel-picker stack full-width') }}>
    @include('waterhole::components.channel-picker-nodes', ['nodes' => $nodes])

    @if ($value)
        <input
            type="hidden"
            name="{{ $name }}"
            value="{{ $value }}"
            @if ($form) form="{{ $form }}" @endif
        />
    @endif
</div>

<div {{ $attributes }}>
    @if ($post->is_locked)
        <x-waterhole::alert icon="heroicon-o-lock-closed" class="alert--lg">
            Comments are locked.
        </x-waterhole::alert>
    @endif
</div>

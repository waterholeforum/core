<div {{ $attributes }}>
    @if ($post->is_locked)
        <x-waterhole::alert icon="tabler-lock" class="alert--lg">
            {{ __('waterhole::forum.comments-locked-message') }}
        </x-waterhole::alert>
    @endif
</div>

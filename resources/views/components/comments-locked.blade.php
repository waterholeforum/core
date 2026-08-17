<x-waterhole::alert
    {{ $attributes }}
    icon="tabler-lock"
    :hidden="! $post->is_locked"
>
    {{ __('waterhole::forum.comments-locked-message') }}
</x-waterhole::alert>

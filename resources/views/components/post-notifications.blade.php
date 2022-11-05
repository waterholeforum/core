@switch ($post->userState->notifications)
    @case('follow')
        <span class="badge bg-warning-soft color-warning">
            <x-waterhole::icon icon="tabler-bell"/>
            <span>{{ __('waterhole::forum.post-following-badge') }}</span>
        </span>
    @break

    @case('ignore')
        <span class="badge">
            <x-waterhole::icon icon="tabler-volume-3"/>
            <span>{{ __('waterhole::forum.post-ignored-badge') }}</span>
        </span>
@endswitch

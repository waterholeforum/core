@switch($post->userState->notifications)
    @case('follow')
        <span class="badge bg-warning-soft">
            @icon('tabler-bell')
            <span>{{ __('waterhole::forum.post-following-badge') }}</span>
        </span>

        @break
    @case('ignore')
        <span class="badge">
            @icon('tabler-eye-off')
            <span>{{ __('waterhole::forum.post-ignored-badge') }}</span>
        </span>
@endswitch

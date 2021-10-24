@switch ($post->userState->notifications)
    @case('follow')
        <span class="with-icon">
            <x-waterhole::icon icon="heroicon-o-bell"/>
            <span>Following</span>
        </span>
    @break

    @case('ignore')
        <span class="with-icon">
            <x-waterhole::icon icon="heroicon-o-volume-off"/>
            <span>Ignored</span>
        </span>
@endswitch

@switch ($post->userState->notifications)
    @case('follow')
        <span class="badge badge--info">
            <x-waterhole::icon icon="heroicon-o-bell"/>
            <span>Following</span>
        </span>
    @break

    @case('ignore')
        <span class="badge">
            <x-waterhole::icon icon="heroicon-o-volume-off"/>
            <span>Ignored</span>
        </span>
@endswitch

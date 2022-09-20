@if ($response === true || $response->allowed())
    <a
        href="{{ route('waterhole.posts.create', ['channel' => $channel?->id]) }}"
        class="btn bg-accent text-md index-create-post"
    >
        {{ __('waterhole::forum.create-post-button') }}
    </a>
@else
    <span class="btn is-disabled text-md index-create-post">
        {{ __('waterhole::forum.create-post-button') }}
        <ui-tooltip>
            {{ $response->message() ?: __('waterhole::system.forbidden-message') }}
        </ui-tooltip>
    </span>
@endif

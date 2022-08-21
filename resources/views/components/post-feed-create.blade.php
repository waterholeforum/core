@php
    use Waterhole\Models\User;

    $gate = Gate::forUser(Auth::user() ?: new User());
    $response = isset($channel) ? $gate->inspect('channel.post', $channel) : $gate->inspect('post.create');
@endphp

@if ($response === true || $response->allowed())
    <a
        href="{{ route('waterhole.posts.create', ['channel' => $channel?->id]) }}"
        class="btn bg-accent"
    >
        New Post
    </a>
@else
    <span class="btn is-disabled">
        New Post
        <ui-tooltip>{{ $response->message() ?: __('waterhole::system.forbidden-message') }}</ui-tooltip>
    </span>
@endif

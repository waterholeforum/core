<div {{ $attributes->class('row wrap gap-sm') }}>
    @php
        $enabled = $response === true || $response->allowed();
        $tag = $enabled ? 'a' : 'span';
        $href = $post->urlAtIndex($post->comment_count) . '#reply';
        if ($enabled && ! Auth::check()) {
            $href = route('waterhole.login', ['return' => $href]);
        }
    @endphp

    <{{ $tag }}
        class="btn grow {{ $enabled ? 'bg-accent' : 'is-disabled' }}"
        @if ($enabled) href="{{ $href }}" @endif
    >
        @icon('tabler-message-circle')

        {{ __('waterhole::forum.post-comment-button') }}

        @unless ($enabled)
            <ui-tooltip>
                {{ $response->message() ?: __('waterhole::system.forbidden-message') }}
            </ui-tooltip>
        @endunless
    </{{ $tag }}>

    <x-waterhole::action-menu
        :for="$post"
        class="grow"
        :button-attributes="['class' => 'btn full-width']"
        placement="bottom-end"
    >
        <x-slot name="button">
            @icon('tabler-settings')
            <span class="hide-sm">{{ __('waterhole::system.controls-button') }}</span>
            @icon('tabler-chevron-down')
        </x-slot>
    </x-waterhole::action-menu>

    @auth
        <div class="hide-sm grow">
            <x-waterhole::follow-button :followable="$post" />
        </div>
    @endauth

    @components(Waterhole\Extend\PostSidebar::build(), compact('post'))
</div>

<div {{ $attributes->class('row wrap gap-sm') }}>
    @php
        $enabled = $response === true || $response->allowed();
        $tag = $enabled ? 'a' : 'span';
    @endphp

    <{{ $tag }}
        class="btn grow hide-sm {{ $enabled ? 'bg-accent' : 'is-disabled' }}"
        @if ($enabled) href="{{ $post->urlAtIndex($post->comment_count) }}#reply" @endif
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
            <span>{{ __('waterhole::system.controls-button') }}</span>
            @icon('tabler-chevron-down')
        </x-slot>
    </x-waterhole::action-menu>

    <div class="hide-sm grow">
        <x-waterhole::follow-button :followable="$post" />
    </div>

    @components(Waterhole\Extend\PostSidebar::build(), compact('post'))
</div>

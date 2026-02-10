<div {{ $attributes->class("post-sidebar text-xs") }}>
    @php
        $enabled = $response === true || $response->allowed();
        $tag = $enabled ? "a" : "span";
        $href = $post->urlAtIndex($post->comment_count) . "#reply";
        if ($enabled && ! Auth::check()) {
            $href = route("waterhole.login", ["return" => $href]);
        }
    @endphp

    <{{ $tag }}
        class="btn btn--transparent btn--start {{ $enabled ? "" : "is-disabled" }}"
        @if ($enabled)
            href="{{ $href }}"
        @endif
    >
        @icon("tabler-share-3", ["class" => "flip-horizontal"])

        {{ __("waterhole::forum.comment-reply-button") }}

        @unless ($enabled)
            <ui-tooltip>
                {{ $response->message() ?: __("waterhole::system.forbidden-message") }}
            </ui-tooltip>
        @endunless
    </{{ $tag }}>

    @auth
        <x-waterhole::action-button
            :for="$post"
            :action="Waterhole\Actions\Bookmark::class"
            class="btn btn--transparent btn--start hide-sm"
        />
    @endauth

    <x-waterhole::action-menu
        :for="$post"
        :button-attributes="['class' => 'btn btn--transparent btn--start']"
        placement="bottom-start"
    >
        <x-slot name="button">
            @icon("tabler-dots-circle-horizontal")
            <span>{{ __("waterhole::system.controls-button") }}</span>
        </x-slot>
    </x-waterhole::action-menu>

    @components(resolve(\Waterhole\Extend\Ui\PostPage::class)->sidebar, compact("post"))
</div>

@props(['post'])

<article class="post-full" id="@domid($post)-full">
    <header class="post-header">
        @components(Waterhole\Extend\PostHeader::getComponents(), compact('post'))
    </header>

    <div class="post-body content">
        {{ emojify($post->body_html) }}
    </div>

{{--    <x-waterhole::post-footer :post="$post" interactive>--}}
{{--        <x-waterhole::action-menu :for="$post"/>--}}
{{--    </x-waterhole::post-footer>--}}
</article>

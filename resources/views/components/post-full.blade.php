<article {{ $attributes->class('post-full with-sidebar-end') }}>
    <div class="post-full__main">
        <header class="post-header">
            @components(Waterhole\Extend\PostHeader::getComponents(), compact('post'))
        </header>

        <div class="post-body content">
            {{ emojify($post->body_html) }}
        </div>
    </div>

    <div
        class=""
        style="margin-top: 6rem; position: sticky; top: calc(var(--header-height) + var(--space-xl)); margin-left: var(--space-xxxl); width: 160px; flex-shrink: 0; padding: 0 0 0 var(--space-md); margin-bottom: 0"
    >
        <div class="toolbar toolbar--nospace">


            @components(Waterhole\Extend\PostFooter::getComponents(), compact('post') + ['interactive' => true])

            <x-waterhole::post-actions :post="$post"/>

        </div>
    </div>

{{--    <x-waterhole::post-footer :post="$post" interactive>--}}
{{--        <x-waterhole::action-menu :for="$post"/>--}}
{{--    </x-waterhole::post-footer>--}}
</article>

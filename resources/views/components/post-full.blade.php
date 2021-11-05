<article {{ $attributes->class('post-full with-sidebar-end') }}>
    <div class="post-full__main">
        <header class="post-header">
            @components(Waterhole\Extend\PostHeader::getComponents(), compact('post'))
        </header>

        <div
            class="post-body content"
            data-controller="quotable"
        >
            {{ emojify($post->body_html) }}

            <a
                href="{{ route('waterhole.posts.comments.create', compact('post')) }}"
                class="quotable-button btn btn--tooltip"
                data-turbo-frame="@domid($post, 'comment_parent')"
                data-quotable-target="button"
                data-action="quotable#quoteSelectedText"
                hidden
            >
                <x-waterhole::icon icon="heroicon-o-annotation"/>
                <span>Quote</span>
            </a>
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

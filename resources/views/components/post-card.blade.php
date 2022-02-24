<article
    {{ $attributes->class(['card post-card', Waterhole\Extend\PostClasses::build($post)]) }}
    data-controller="post"
>
    <header class="post-card__header">
        <x-waterhole::post-summary :post="$post"/>
        <x-waterhole::post-actions :post="$post"/>
    </header>

    <div class="post-card__content content content--compact text-sm">
        @php
            $string = $post->body_html;

            //$excerpt = (new Marcgoertz\Shorten\Shorten())->truncateMarkup($string, 300, '...', true);
            $excerpt = Waterhole\Support\Text::truncate($string, 500, [
                'exact' => false,
                'html' => true,
                'ellipsis' => '...'
            ]);
        @endphp
{{--        <pre>{{ e($post->body_html) }}</pre>--}}
        {!! Waterhole\emojify($excerpt) !!}
{{--        {!! Waterhole\emojify($post->body_html) !!}--}}
        @if (str_ends_with(strip_tags($excerpt), '...'))
            <p><a href="{{ $post->url }}" style="font-weight: var(--font-weight-bold)">Read more</a></p>
        @endif
    </div>

    <x-waterhole::post-footer :post="$post" interactive/>
</article>

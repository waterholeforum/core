<div class="post-footer row gap-xs wrap">
    @components(Waterhole\Extend\PostFooter::build(), compact('post', 'interactive'))
    {{ $slot }}
</div>

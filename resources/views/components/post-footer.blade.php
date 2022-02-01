<div class="post-footer toolbar">
    @components(Waterhole\Extend\PostFooter::build(), compact('post', 'interactive'))
    {{ $slot }}
</div>

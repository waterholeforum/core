<div class="post-footer toolbar">
    @components(Waterhole\Extend\PostFooter::getComponents(), compact('post', 'interactive'))
    {{ $slot }}
</div>

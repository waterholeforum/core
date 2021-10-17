@props(['post', 'interactive' => false])

<div class="post-footer">
    @components(Waterhole\Extend\PostFooter::getComponents(), compact('post', 'interactive'))
    {{ $slot }}
</div>

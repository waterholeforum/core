@props(['post'])

<div class="post-footer">
    @components(Waterhole\Extend\PostFooter::getComponents(), compact('post'))
</div>

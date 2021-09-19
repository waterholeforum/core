@props(['feed', 'channel' => null])

<div class="toolbar index-toolbar">
    @components(Waterhole\Extend\FeedToolbar::getComponents(), compact('feed', 'channel'))
</div>

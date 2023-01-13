<div class="feed__toolbar row gap-xs align-start">
    <div class="grow row wrap gap-x-xs gap-y-sm">
        @components(Waterhole\Extend\PostFeedToolbar::build(), compact('feed', 'channel'))
    </div>
    <x-waterhole::post-feed-controls :feed="$feed" :channel="$channel" class="push-end"/>
</div>

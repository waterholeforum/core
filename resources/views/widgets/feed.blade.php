<div class="card stack-md">
    <turbo-frame
        id="feed_{{ sha1($url) }}"
        src="{{ route('waterhole.admin.dashboard.feed', compact('url', 'limit')) }}"
        data-controller="turbo-frame"
        data-action="turbo:frame-load->turbo-frame#disable"
    >
        <div class="loading-indicator">Loading Feed</div>
    </turbo-frame>
</div>

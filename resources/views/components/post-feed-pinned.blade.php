<div class="post-feed__pinned grid gap-md hide-if-empty">
    @foreach ($posts as $post)
        <x-waterhole::pinned-post :$post />
    @endforeach
</div>

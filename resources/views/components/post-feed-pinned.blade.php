<div class="post-feed__pinned grid-fit gap-md hide-if-empty">
    @foreach ($posts as $post)
        <x-waterhole::pinned-post :$post />
    @endforeach
</div>

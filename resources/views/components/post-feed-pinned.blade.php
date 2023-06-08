<div class="post-feed__pinned grid-fit gap-md">
    @foreach ($posts as $post)
        <x-waterhole::pinned-post :$post/>
    @endforeach
</div>

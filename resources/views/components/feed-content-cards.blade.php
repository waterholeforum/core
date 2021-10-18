@props(['posts'])

<div class="post-cards">
    @foreach ($posts as $post)
        <x-waterhole::post-card :post="$post"/>
    @endforeach
</div>

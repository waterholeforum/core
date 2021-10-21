@props(['posts'])

<div class="post-cards">
    @foreach ($posts as $post)
        <x-waterhole::post-cards-item :post="$post"/>
    @endforeach
</div>

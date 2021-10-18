@props(['posts'])

<ul class="post-list card-list">
    @foreach ($posts as $post)
        <x-waterhole::post-list-item :post="$post"/>
    @endforeach
</ul>

@props(['posts'])

<ul class="post-list card-list" role="list">
    @foreach ($posts as $post)
        <x-waterhole::post-list-item :post="$post"/>

        {{ $after ?? '' }}
    @endforeach
</ul>

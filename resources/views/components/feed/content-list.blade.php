@props(['posts'])

<ul class="post-list card-list">
    @foreach ($posts as $post)
        <li class="post-list-item {{ Waterhole\Extend\PostClasses::getClasses($post) }}">
            <div class="post-list-item__content">
                <x-waterhole::posts.summary :post="$post"/>
                <x-waterhole::post-footer :post="$post"/>
            </div>
            <x-waterhole::actions.menu :for="$post" class="post-list-item__controls" placement="bottom-end"/>
        </li>
    @endforeach
</ul>

{{ $posts->links() }}

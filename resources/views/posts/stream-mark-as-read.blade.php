@foreach ($posts as $post)
    <turbo-stream target="@domid($post, 'list-item')" action="replace">
        <template>
            <x-waterhole::post-list-item :post="$post"/>
        </template>
    </turbo-stream>

    <turbo-stream target="@domid($post, 'card')" action="replace">
        <template>
            <x-waterhole::post-card :post="$post"/>
        </template>
    </turbo-stream>
@endforeach

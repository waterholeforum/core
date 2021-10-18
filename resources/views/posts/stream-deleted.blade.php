@foreach ($posts as $post)
    <turbo-stream target="@domid($post, 'card')" action="remove"></turbo-stream>
    <turbo-stream target="@domid($post, 'list-item')" action="remove"></turbo-stream>
@endforeach

<turbo-stream target="modal" action="append">
    <template>
        <stimulus-invoke action="modal#hide"></stimulus-invoke>
    </template>
</turbo-stream>

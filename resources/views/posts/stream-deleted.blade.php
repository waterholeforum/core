@foreach ($posts as $post)
    <turbo-stream target="@domid($post)" action="remove"></turbo-stream>
@endforeach

<turbo-stream target="modal" action="append">
    <template>
        <stimulus-invoke action="modal#hide"></stimulus-invoke>
    </template>
</turbo-stream>

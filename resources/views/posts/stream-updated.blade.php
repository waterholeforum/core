@foreach ($posts as $post)
    <turbo-stream target="@domid($post)-summary" action="replace">
        <template>
            <x-waterhole::post-summary :post="$post"/>
        </template>
    </turbo-stream>

    <turbo-stream target="@domid($post)-full" action="replace">
        <template>
            <x-waterhole::post-full :post="$post"/>
        </template>
    </turbo-stream>

    <turbo-stream target="modal" action="append">
        <template>
            <stimulus-invoke action="modal#hide"></stimulus-invoke>
        </template>
    </turbo-stream>
@endforeach

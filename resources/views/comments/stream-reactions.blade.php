@foreach ($comments as $comment)
    <turbo-stream target="@domid($comment, 'reactions')" action="replace">
        <template>
            <x-waterhole::comment-reactions :comment="$comment"/>
        </template>
    </turbo-stream>
@endforeach

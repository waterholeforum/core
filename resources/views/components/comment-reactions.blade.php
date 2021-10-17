@props(['comment'])

<div id="@domid($comment, 'reactions')" @unless ($comment->score) hidden @endunless>
    @if ($comment->score)
        <form action="{{ route('waterhole.action.store') }}" method="POST">
            @csrf
            <input type="hidden" name="actionable" value="comments">
            <input type="hidden" name="id[]" value="{{ $comment->id }}">
            <input type="hidden" name="action_class" value="{{ Waterhole\Actions\React::class }}">

            <button type="submit" {{ $attributes->class([
                'btn btn--small btn--outline',
                'is-active' => $comment->likedBy->contains(Auth::id())
            ]) }}>
                <x-waterhole::icon icon="👍"/>
                <span>{{ $comment->score }}</span>
            </button>
        </form>
    @endif
</div>

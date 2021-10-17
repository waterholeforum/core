<div id="@domid($model, 'reactions')" @unless ($model->score) hidden @endunless>
    @if ($model->score)
        <x-waterhole::action-form
            :for="$model"
            :action="Waterhole\Actions\React::class"
        >
            <button type="submit" {{ $attributes->class([
                'btn btn--small btn--outline',
                'is-active' => $model->likedBy->contains(Auth::id())
            ]) }}>
                <x-waterhole::icon icon="👍"/>
                <span>{{ $model->score }}</span>
            </button>
        </x-waterhole::action-form>
    @endif
</div>

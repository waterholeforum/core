<div id="@domid($model, 'reactions')" @unless ($model->score) hidden @endunless>
    @if ($model->score)
        <x-waterhole::action-form
            :for="$model"
            :action="Waterhole\Actions\React::class"
        >
            <{{ $component->isAuthorized ? 'button type="submit"' : 'span' }} {{ $attributes->class([
                'btn btn--small btn--outline',
                'is-active' => $model->likedBy->contains(Auth::id()),
            ]) }}>
                <x-waterhole::icon icon="emoji:👍"/>
                <span>{{ $model->score }}</span>
            </{{ $component->isAuthorized ? 'button' : 'span' }}>
        </x-waterhole::action-form>
    @endif
</div>

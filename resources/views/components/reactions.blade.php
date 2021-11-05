<div {{ $attributes }} @unless ($model->score) hidden @endunless>
    @if ($model->score)
        <x-waterhole::action-form
            :for="$model"
            :action="Waterhole\Actions\Like::class"
            :return="$model->url"
        >
            <{{ $component->isAuthorized ? 'button type="submit"' : 'span' }} {{ $attributes->class([
                'btn btn--small btn--outline',
                'is-active' => $model->likedBy->contains(Auth::id()),
            ]) }}>
                <x-waterhole::icon icon="emoji:👍"/>
                <span>{{ $model->score }}</span>

                <ui-tooltip tooltip-class="tooltip tooltip--block">
                    <strong>Like</strong>
                    <ul role="list">
                        @foreach ($model->likedBy->take(20) as $user)
                            <li>{{ $user->name }}</li>
                        @endforeach
                        @if ($model->likedBy->count() > 20)
                            <li>{{ $model->likedBy->count() - 20 }} others</li>
                        @endif
                    </ul>
                </ui-tooltip>
            </{{ $component->isAuthorized ? 'button' : 'span' }}>
        </x-waterhole::action-form>
    @endif
</div>

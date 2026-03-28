<div {{ $attributes->class('reactions row wrap gap-xxs') }}>
    @foreach ($reactionTypes as $reactionType)
        @php
            $buttonAttributes = [
                'data-reaction-type' => $reactionType->id,
                'data-count' => ($count = $reactionCount($reactionType)),
            ];

            if ($isAuthorized) {
                $buttonAttributes += [
                    'name' => 'reaction_type_id',
                    'value' => $reactionType->id,
                    'form' => 'action-form',
                    'formaction' => route('waterhole.actions.store', [
                        'actionable' => get_class($model),
                        'id' => $model->getKey(),
                        'action_class' => Waterhole\Actions\React::class,
                        'return' => $model->url,
                    ]),
                    'formmethod' => 'POST',
                    'formnovalidate' => true,
                ];

                if ($reactionSet->reactionTypes->count() === 1) {
                    $buttonAttributes['data-shortcut-trigger'] = 'selection.react';
                }
            }
        @endphp

        <{{ $isAuthorized ? 'button' : 'span' }}
            {{
                (new Illuminate\View\ComponentAttributeBag($buttonAttributes))->class([
                    'btn btn--sm btn--outline reaction',
                    'is-active' => $userReacted($reactionType),
                    'is-inert' => ! $isAuthorized,
                ])
            }}
        >
            @icon($reactionType->icon)
            <span>{{ $count }}</span>

            <ui-tooltip tooltip-class="tooltip tooltip--block">
                <strong>{{ $reactionType->name }}</strong>
                @if ($isAuthorized && $reactionSet->reactionTypes->count() === 1)
                    <x-waterhole::shortcut-label shortcut="selection.react" />
                @endif

                @if ($count)
                    <turbo-frame
                        id="reactions"
                        src="{{ $model->reactionsUrl($reactionType) }}"
                        loading="lazy"
                    >
                        Loading...
                    </turbo-frame>
                @endif
            </ui-tooltip>
        </{{ $isAuthorized ? 'button' : 'span' }}>
    @endforeach

    @if ($isAuthorized && $reactionSet->reactionTypes->count() > 1)
        <ui-popup placement="top" class="js-only">
            <button
                type="button"
                class="btn btn--sm btn--icon btn--transparent control"
                data-shortcut-trigger="selection.react"
            >
                @icon('tabler-mood-plus')
                <ui-tooltip>
                    {{ __('waterhole::forum.add-reaction-button') }}
                    <x-waterhole::shortcut-label shortcut="selection.react" />
                </ui-tooltip>
            </button>

            <ui-menu class="menu reactions-menu" hidden>
                @foreach ($reactionSet->reactionTypes as $reactionType)
                    <button
                        class="text-xl reaction-type-{{ $reactionType->id }}"
                        name="reaction_type_id"
                        value="{{ $reactionType->id }}"
                        form="action-form"
                        formaction="{{
                            route('waterhole.actions.store', [
                                'actionable' => get_class($model),
                                'id' => $model->getKey(),
                                'action_class' => Waterhole\Actions\React::class,
                                'return' => $model->url,
                            ])
                        }}"
                        formmethod="POST"
                        formnovalidate
                        role="menuitemradio"
                    >
                        @icon($reactionType->icon)
                        <ui-tooltip>{{ $reactionType->name }}</ui-tooltip>
                    </button>
                @endforeach
            </ui-menu>
        </ui-popup>
    @endif
</div>

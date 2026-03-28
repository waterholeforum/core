<button
    {{
        $attributes
            ->merge([
                'type' => 'submit',
                'name' => 'action_class',
                'value' => Waterhole\Actions\MarkAsRead::class,
                'form' => 'action-form',
                'data-shortcut-trigger' => 'action.mark-as-read',
                'formaction' => route('waterhole.actions.store', [
                    'actionable' => get_class($post),
                    'id' => $post->getKey(),
                    'return' => request()->fullUrl(),
                ]),
                'formmethod' => 'POST',
                'formnovalidate' => true,
            ])
            ->class(['post-list-item__unread badge clickable', 'bg-activity' => $isNotifiable])
    }}
>
    @if ($isNotifiable)
        @icon('tabler-bell')
    @endif

    @if ($post->isNew())
        <span>{{ __('waterhole::forum.post-new-badge') }}</span>
        <ui-tooltip placement="bottom">
            {{ __('waterhole::forum.post-new-badge-tooltip') }}
            <br />
            <small>{{ __('waterhole::forum.mark-as-read-instruction') }}</small>
        </ui-tooltip>
    @else
        <span>{{ $post->unread_comments_count }}</span>
        <ui-tooltip placement="bottom">
            {{ __('waterhole::forum.post-unread-comments-badge-tooltip', ['count' => $post->unread_comments_count]) }}
            <br />
            <small>{{ __('waterhole::forum.mark-as-read-instruction') }}</small>
        </ui-tooltip>
    @endif
</button>

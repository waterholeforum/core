<div class="grow row gap-md justify-between">
    @if (isset($user))
        <x-waterhole::user-label :$user />

        @if ($commentId ?? null)
            <span class="with-icon color-muted text-xxs">
                @icon('tabler-share-3', ['class' => 'flip-horizontal'])
                {{ __('waterhole::forum.comment-reply-button') }}
            </span>
        @endif
    @elseif (isset($group))
        <x-waterhole::group-badge :group="$group" class="-my-xxs" />

        @if ($group->hasAttribute('users_count'))
            <span class="with-icon color-muted text-xxs">
                @icon('tabler-users')
                {{ $group->users_count }}
            </span>
        @endif
    @endif
</div>

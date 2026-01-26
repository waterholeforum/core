@props([
    'group',
])

<li class="card__row row gap-md">
    @if ($group->isMember())
        <span class="row gap-xxs text-xs color-muted">
            @icon('tabler-user')
            <span>{{ __('waterhole::cp.structure-visibility-members-label') }}</span>
        </span>
    @elseif ($group->isGuest())
        <span class="row gap-xxs text-xs color-muted">
            @icon('tabler-world')
            <span>{{ __('waterhole::cp.structure-visibility-public-label') }}</span>
        </span>
    @else
        <x-waterhole::group-badge :group="$group" class="text-xs" />
    @endif

    <div class="grow"></div>

    @unless ($group->isGuest() || $group->isMember())
        <a href="{{ $group->users_url }}" class="color-muted text-xs">
            {{ __('waterhole::cp.group-user-count', ['count' => $group->users_count]) }}
        </a>
    @endunless

    <x-waterhole::action-buttons :for="$group" :limit="2" context="cp" class="text-xs" />
</li>

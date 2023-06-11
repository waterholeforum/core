<div class="stack gap-lg">
    <h1 class="h3">
        {{ __('waterhole::user.suspend-user-title') }}
        @if ($users->count() === 1)
            <x-waterhole::user-label :user="$users[0]" />
        @endif
    </h1>

    <div class="stack gap-sm" data-controller="reveal">
        <label class="choice">
            <input
                type="radio"
                name="status"
                value="none"
                data-reveal-target="if"
                @checked(! $users[0]->suspended_until)
            />
            {{ __('waterhole::user.not-suspended-label') }}
        </label>

        <label class="choice">
            <input
                type="radio"
                name="status"
                value="indefinite"
                data-reveal-target="if"
                @checked($indefinite = $users[0]->suspended_until?->year === 2038)
            />
            {{ __('waterhole::user.suspended-indefinitely-label') }}
        </label>

        <label class="choice">
            <input
                type="radio"
                name="status"
                value="custom"
                data-reveal-target="if"
                @checked($users[0]->suspended_until && ! $indefinite)
            />
            {{ __('waterhole::user.suspended-until-label') }}
        </label>

        <span class="choice" data-reveal-target="then" data-reveal-value="custom">
            <input
                type="datetime-local"
                name="suspended_until"
                value="{{ $indefinite ? '' : $users[0]->suspended_until }}"
            />
        </span>
    </div>
</div>

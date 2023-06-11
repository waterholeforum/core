<div class="stack gap-lg">
    <h1 class="h3">
        {{ __('waterhole::cp.delete-user-title', ['count' => $users->count()]) }}
        @if ($users->count() === 1)
            <x-waterhole::user-label :user="$users[0]" />
        @endif
    </h1>

    <div class="stack gap-sm">
        <label class="choice">
            <input type="radio" name="delete_content" value="0" checked />
            {{ __('waterhole::cp.keep-user-content-label') }}
        </label>

        <label class="choice">
            <input type="radio" name="delete_content" value="1" />
            {{ __('waterhole::cp.delete-user-content-label') }}
        </label>
    </div>
</div>

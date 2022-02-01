<div class="stack-lg">
    <h1 class="h3">
        @if ($users->count() === 1)
            Delete User: <x-waterhole::user-label :user="$users[0]"/>
        @else
            Delete {{ $users->count() }} Users
        @endif
    </h1>

    <div class="stack-sm">
        <label class="choice">
            <input type="radio" name="delete_content" value="0" checked>
            Keep content and mark as anonymous
        </label>

        <label class="choice">
            <input type="radio" name="delete_content" value="1">
            Delete content permanently
        </label>
    </div>
</div>

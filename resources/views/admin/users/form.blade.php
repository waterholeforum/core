@php
    $title = isset($user) ? 'Edit User' : 'Create a User';
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.users.index')"
        parent-title="Users"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($user) ? route('waterhole.admin.users.update', compact('user')) : route('waterhole.admin.users.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @isset($user) @method('PATCH') @endif

        <div class="stack-lg">
            <x-waterhole::validation-errors/>

            <div class="panels">
                <details class="panel" open>
                    <summary class="panel__header h4">Account</summary>

                    <div class="panel__body form-groups">
                        <x-waterhole::field name="name" label="Name">
                            <input
                                type="text"
                                name="name"
                                id="{{ $component->id }}"
                                class="input"
                                value="{{ old('name', $user->name ?? null) }}"
                                autofocus
                            >
                        </x-waterhole::field>

                        <x-waterhole::field name="email" label="Email">
                            <input
                                type="email"
                                name="email"
                                id="{{ $component->id }}"
                                class="input"
                                value="{{ old('email', $user->email ?? null) }}"
                            >
                        </x-waterhole::field>

                        <x-waterhole::field name="password" label="Password">
                            @isset($user)
                                <div data-controller="reveal" class="stack-sm">
                                    <label class="choice">
                                        <input type="checkbox" data-reveal-target="if">
                                        Set new password
                                    </label>
                            @endisset
                                    <input
                                        data-reveal-target="then"
                                        type="password"
                                        name="password"
                                        id="{{ $component->id }}"
                                        class="input"
                                    >
                            @isset($user)
                                </div>
                            @endisset
                        </x-waterhole::field>

                        <div>
                            <div class="field__label">Groups</div>
                            <div class="stack-sm">
                                @foreach ($groups as $group)
                                    <label class="choice">
                                        <input
                                            type="checkbox"
                                            name="groups[]"
                                            value="{{ $group->id }}"
                                            @if (in_array($group->id, old('groups', isset($user) ? $user->groups->pluck('id')->all() : []))) checked @endif
                                            @if ($group->isAdmin() && ($user->id ?? null) === 1) disabled @endif
                                        >
                                        <x-waterhole::group-label :group="$group"/>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </details>

                <details class="panel">
                    <summary class="panel__header h4">Profile</summary>

                    <div class="panel__body form-groups">
                        <x-waterhole::user-profile-fields :user="$user ?? null"/>
                    </div>
                </details>
            </div>

            <div class="toolbar">
                <button
                    type="submit"
                    class="btn btn--primary btn--wide"
                >
                    {{ isset($user) ? 'Save Changes' : 'Create' }}
                </button>
                <a
                    href="{{ route('waterhole.admin.users.index') }}"
                    class="btn"
                    onclick="window.history.back(); return false"
                >Cancel</a>
            </div>
        </div>
    </form>
</x-waterhole::admin>

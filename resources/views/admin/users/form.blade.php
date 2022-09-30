@php
    $title = isset($user)
        ? __('waterhole::admin.edit-user-title')
        : __('waterhole::admin.create-user-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.users.index')"
        :parent-title="__('waterhole::admin.users-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($user) ? route('waterhole.admin.users.update', compact('user')) : route('waterhole.admin.users.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @isset($user) @method('PATCH') @endif
        @return

        <input type="hidden" name="return" value="{{ old('return', request('return')) }}">

        <div class="stack gap-lg">
            <x-waterhole::validation-errors/>

            <div class="stack gap-md">
                <details class="card" open>
                    <summary class="card__header h5">
                        {{ __('waterhole::admin.user-account-title') }}
                    </summary>

                    <div class="card__body form-groups">
                        <x-waterhole::field
                            name="name"
                            :label="__('waterhole::admin.user-name-label')"
                        >
                            <input
                                type="text"
                                name="name"
                                id="{{ $component->id }}"
                                class="input"
                                value="{{ old('name', $user->name ?? null) }}"
                                autofocus
                            >
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="email"
                            :label="__('waterhole::admin.user-email-label')"
                        >
                            <input
                                type="email"
                                name="email"
                                id="{{ $component->id }}"
                                class="input"
                                value="{{ old('email', $user->email ?? null) }}"
                            >
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="password"
                            :label="__('waterhole::admin.user-password-label')"
                        >
                            @isset($user)
                                <div data-controller="reveal" class="stack gap-sm">
                                    <label class="choice">
                                        <input type="checkbox" data-reveal-target="if">
                                        {{ __('waterhole::admin.user-set-password-label') }}
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

                        <div class="field">
                            <div class="field__label">
                                {{ __('waterhole::admin.user-groups-label') }}
                            </div>
                            <div class="stack gap-sm">
                                <input type="hidden" name="groups" value="">

                                @foreach ($groups as $group)
                                    <label class="choice">
                                        <input
                                            type="checkbox"
                                            name="groups[]"
                                            value="{{ $group->id }}"
                                            @checked(in_array($group->id, (array) old('groups', isset($user) ? $user->groups->pluck('id')->all() : [])))
                                            @disabled($enforce = $group->isAdmin() && $user?->isRootAdmin())
                                        >
                                        <x-waterhole::group-label :group="$group"/>
                                        @if ($enforce)
                                            <input type="hidden" name="groups[]" value="{{ $group->id }}">
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </details>

                <details class="card">
                    <summary class="card__header h5">
                        {{ __('waterhole::admin.user-profile-title') }}
                    </summary>

                    <div class="card__body form-groups">
                        <x-waterhole::user-profile-fields :user="$user ?? null"/>
                    </div>
                </details>
            </div>

            <div class="row gap-xs wrap">
                <button
                    type="submit"
                    class="btn bg-accent btn--wide"
                >
                    {{ isset($user) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                </button>

                <x-waterhole::cancel
                    :default="route('waterhole.admin.users.index')"
                    class="btn"
                />
            </div>
        </div>
    </form>
</x-waterhole::admin>

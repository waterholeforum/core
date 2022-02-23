<x-waterhole::layout :title="__('waterhole::auth.register-title')">
    <x-waterhole::dialog
        :title="__('waterhole::auth.register-title')"
        class="dialog--sm"
    >
        <form
            action="{{ route('waterhole.register') }}"
            class="form"
            method="POST"
        >
            @csrf

            <x-waterhole::validation-errors/>

            <x-waterhole::field
                name="name"
                :label="__('waterhole::auth.name-label')"
            >
                <input
                    class="input"
                    type="text"
                    id="{{ $component->id }}"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                >
            </x-waterhole::field>

            <x-waterhole::field
                name="email"
                :label="__('waterhole::auth.email-label')"
            >
                <input
                    class="input"
                    type="email"
                    id="{{ $component->id }}"
                    name="email"
                    value="{{ old('email') }}"
                    required
                >
            </x-waterhole::field>

            <x-waterhole::field
                name="password"
                :label="__('waterhole::auth.password-label')"
            >
                <input
                    class="input"
                    type="password"
                    id="{{ $component->id }}"
                    name="password"
                    required
                    autocomplete="new-password"
                >
            </x-waterhole::field>

            <button type="submit" class="btn btn--primary btn--block">
                {{ __('waterhole::auth.register-submit') }}
            </button>

            <p class="text-center">
                {{ __('waterhole::auth.register-login-prompt') }}
                <a href="{{ route('waterhole.login') }}">{{ __('waterhole::auth.register-login-link') }}</a>
            </p>
        </form>
    </x-waterhole::dialog>
</x-waterhole::layout>

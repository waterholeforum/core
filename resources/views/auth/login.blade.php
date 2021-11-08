<x-waterhole::layout :title="__('waterhole::auth.login-title')">
    <x-waterhole::dialog :title="__('waterhole::auth.login-title')" class="dialog--sm">
        <form action="{{ route('waterhole.login') }}" method="POST" class="form" data-controller="login">
            @csrf

            <x-waterhole::validation-errors :errors="$errors"/>

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
                    autofocus
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
                    autocomplete="current-password"
                >
            </x-waterhole::field>

            <div class="toolbar">
                <div>
                    <label for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember">
                        {{ __('waterhole::auth.remember-me-label') }}
                    </label>
                </div>

                <div class="spacer"></div>

                <div>
                    <a href="{{ route('waterhole.forgot-password') }}">
                        {{ __('waterhole::auth.forgot-password-link') }}
                    </a>
                </div>
            </div>

            <button type="submit" class="btn btn--primary btn--block">{{ __('waterhole::auth.login-submit') }}</button>

            <p class="text-center">
                Don't have an account?
                <a href="{{ route('waterhole.register') }}">Sign Up</a>
            </p>
        </form>
    </x-waterhole::dialog>
</x-waterhole::layout>

<x-waterhole::layout :title="__('waterhole::auth.login-title')">
    <div class="container section">
        <x-waterhole::dialog
            :title="__('waterhole::auth.login-title')"
            class="dialog--sm"
        >
            {{--
                Opt-out of Turbo so that any fragment that may be present in the
                redirect URL will be followed. Also, redirect URL may be external.
            --}}
            <form
                action="{{ route('waterhole.login') }}"
                data-controller="login"
                data-turbo="false"
                method="POST"
            >
                @csrf

                <div class="form">
                    @section('email')
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
                    @endsection

                    @section('password')
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

                        <div class="row gap-sm wrap">
                            <div>
                                <label for="remember_me" class="choice">
                                    <input id="remember_me" type="checkbox" name="remember">
                                    {{ __('waterhole::auth.remember-me-label') }}
                                </label>
                            </div>

                            <div class="grow"></div>

                            <div>
                                <a href="{{ route('waterhole.forgot-password') }}" data-turbo="true">
                                    {{ __('waterhole::auth.forgot-password-link') }}
                                </a>
                            </div>
                        </div>
                    @endsection

                    @section('submit')
                        <button type="submit" class="btn bg-accent full-width">
                            {{ __('waterhole::auth.login-submit') }}
                        </button>
                    @endsection

                    @section('sign-up-link')
                        <p class="text-center">
                            {{ __('waterhole::auth.login-register-prompt') }}
                            <a href="{{ route('waterhole.register') }}" data-turbo="true">
                                {{ __('waterhole::auth.login-register-link') }}
                            </a>
                        </p>
                    @endsection

                    @components(Waterhole\Extend\LoginForm::build())
                </div>
            </form>
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>

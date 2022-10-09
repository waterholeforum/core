<x-waterhole::layout :title="__('waterhole::auth.register-title')">
    <div class="container section">
        <x-waterhole::dialog
            :title="__('waterhole::auth.register-title')"
            class="dialog--sm"
        >
            {{--
                Opt-out of Turbo so that any fragment that may be present in the
                redirect URL will be followed. Also, redirect URL may be external.
            --}}
            <form
                action="{{ route('waterhole.register') }}"
                data-turbo="false"
                method="POST"
            >
                @csrf

                <div class="form">
                    <x-waterhole::validation-errors/>

                    @section('username')
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
                    @endsection

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
                                autocomplete="new-password"
                            >
                        </x-waterhole::field>
                    @endsection

                    @section('submit')
                        <button type="submit" class="btn bg-accent full-width">
                            {{ __('waterhole::auth.register-submit') }}
                        </button>
                    @endsection

                    @section('log-in-link')
                        <p class="text-center">
                            {{ __('waterhole::auth.register-login-prompt') }}
                            <a href="{{ route('waterhole.login') }}">{{ __('waterhole::auth.register-login-link') }}</a>
                        </p>
                    @endsection

                    @components(Waterhole\Forms\UserRegisterForm::fields())
                </div>
            </form>
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>

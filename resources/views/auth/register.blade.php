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

                    @components($form->fields())

                    <button type="submit" class="btn bg-accent full-width">
                        {{ __('waterhole::auth.register-submit') }}
                    </button>

                    <p class="text-center">
                        {{ __('waterhole::auth.register-login-prompt') }}
                        <a href="{{ route('waterhole.login') }}">{{ __('waterhole::auth.register-login-link') }}</a>
                    </p>
                </div>
            </form>
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>

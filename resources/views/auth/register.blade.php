<x-waterhole::layout :title="__('waterhole::auth.register-title')">
    <div class="container section">
        <x-waterhole::dialog :title="__('waterhole::auth.register-title')" class="dialog--sm">
            {{--
                Opt-out of Turbo so that any fragment that may be present in the
                redirect URL will be followed. Also, redirect URL may be external.
            --}}
            <form
                action="{{ route('waterhole.register.submit') }}"
                data-turbo="false"
                method="POST"
            >
                @csrf

                @if (request('payload'))
                    <input type="hidden" name="payload" value="{{ request('payload') }}" />
                @endif

                <div class="stack gap-xl">
                    <x-waterhole::validation-errors />

                    @unless ($form->payload)
                        <x-waterhole::auth-buttons />
                    @endunless

                    @if ($form->payload || config('waterhole.auth.password_enabled'))
                        @components($form->fields())

                        <div>
                            <button type="submit" class="btn bg-accent full-width">
                                {{ __('waterhole::auth.register-submit') }}
                            </button>
                        </div>

                        @unless ($form->payload)
                            <p class="text-center">
                                {{ __('waterhole::auth.register-login-prompt') }}
                                <a href="{{ route('waterhole.login') }}">
                                    {{ __('waterhole::auth.register-login-link') }}
                                </a>
                            </p>
                        @endunless
                    @endif
                </div>
            </form>
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>

<x-waterhole::user-profile
    :user="Auth::user()"
    :title="__('waterhole::user.account-settings-title')"
>
    <div class="card form-groups">
        <div>
            <h4 class="field__label">
                {{ __('waterhole::auth.name-label') }}
            </h4>
            <div>
                {{ Auth::user()->name }}
            </div>
        </div>

        <div>
            <h4 class="field__label">
                {{ __('waterhole::auth.email-label') }}
            </h4>
            <turbo-frame id="change-email">
                <form action="{{ route('waterhole.preferences.email') }}" method="POST">
                    @csrf
                    <x-waterhole::field name="email">
                        <div class="row gap-xs">
                            <input
                                class="input grow"
                                name="email"
                                type="email"
                                value="{{ old('email', Auth::user()->email) }}"
                            >
                            <button class="btn">
                                {{ __('waterhole::system.change-button') }}
                            </button>
                        </div>
                    </x-waterhole::field>
                </form>
            </turbo-frame>
        </div>

        <div>
            <h4 class="field__label">
                {{ __('waterhole::auth.password-label') }}
            </h4>
            <turbo-frame id="change-password">
                <form action="{{ route('waterhole.preferences.password') }}" method="POST">
                    @csrf
                    <x-waterhole::field name="password">
                        <div class="row gap-xs">
                            <input
                                autocomplete="new-password"
                                class="input grow"
                                name="password"
                                placeholder="{{ __('waterhole::auth.new-password-label') }}"
                                type="password"
                            >
                            <button class="btn">
                                {{ __('waterhole::system.change-button') }}
                            </button>
                        </div>
                    </x-waterhole::field>
                </form>
            </turbo-frame>
        </div>

{{--        <div>--}}
{{--            <button class="btn btn--danger">--}}
{{--                {{ __('waterhole::user.delete-account-button') }}--}}
{{--            </button>--}}
{{--        </div>--}}
    </div>
</x-waterhole::user-profile>

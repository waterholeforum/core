@php
    $title = __('waterhole::user.account-settings-title');
@endphp

<x-waterhole::user-profile :user="Auth::user()" :title="$title">
    <h2 class="visually-hidden">{{ $title }}</h2>

    <div class="card card__body stack dividers">
        @section('name')
            <div class="field">
                <h4 class="field__label">
                    {{ __('waterhole::auth.name-label') }}
                </h4>
                <div>
                    {{ Auth::user()->name }}
                </div>
            </div>
        @endsection

        @section('email')
            <div class="field">
                <h4 class="field__label">
                    {{ __('waterhole::auth.email-label') }}
                </h4>
                <turbo-frame id="change-email">
                    <form action="{{ route('waterhole.preferences.email') }}" method="POST">
                        @csrf
                        <x-waterhole::field name="email">
                            <div class="row gap-xs">
                                <input
                                    class="grow"
                                    name="email"
                                    type="email"
                                    value="{{ old('email', Auth::user()->email) }}"
                                />
                                <button class="btn">
                                    {{ __('waterhole::system.change-button') }}
                                </button>
                            </div>
                        </x-waterhole::field>
                    </form>
                </turbo-frame>
            </div>
        @endsection

        @section('password')
            <div class="field">
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
                                    class="grow"
                                    name="password"
                                    placeholder="{{ __('waterhole::auth.new-password-label') }}"
                                    type="password"
                                />
                                <button class="btn">
                                    {{ __('waterhole::system.change-button') }}
                                </button>
                            </div>
                        </x-waterhole::field>
                    </form>
                </turbo-frame>
            </div>
        @endsection

        @section('delete')
            <x-waterhole::action-button
                :for="Auth::user()"
                :action="Waterhole\Actions\DeleteSelf::class"
                class="btn bg-danger"
            />
        @endsection

        @components(Waterhole\Extend\PreferencesAccount::components())
    </div>
</x-waterhole::user-profile>

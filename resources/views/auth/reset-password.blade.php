<x-waterhole::layout :title="__('waterhole::auth.reset-password-title')">
    <x-waterhole::dialog :title="__('waterhole::auth.reset-password-title')" class="dialog--sm">
        <form action="{{ route('waterhole.reset-password', ['token' => $request->route('token')]) }}" method="POST" class="form">
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
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                >
            </x-waterhole::field>

            <x-waterhole::field
                name="password"
                :label="__('waterhole::auth.new-password-label')"
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

            <x-waterhole::field
                name="password_confirmation"
                :label="__('waterhole::auth.confirm-password-label')"
            >
                <input
                    class="input"
                    type="password"
                    id="{{ $component->id }}"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                >
            </x-waterhole::field>

            <button type="submit" class="btn btn--primary btn--block">{{ __('waterhole::auth.reset-password-submit') }}</button>
        </form>
    </x-waterhole::dialog>
</x-waterhole::layout>
